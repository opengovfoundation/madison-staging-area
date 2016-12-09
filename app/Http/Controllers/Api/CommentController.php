<?php

namespace App\Http\Controllers\Api;

use Auth;
use Input;
use Response;
use Event;
use DB;
use App\Models\Annotation;
use App\Models\AnnotationPermission;
use App\Models\Doc;
use App\Models\User;
use App\Events\CommentCreated;
use App\Events\FeedbackSeen;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use League\Csv\Writer;
use App\Http\Requests\Api\DocAccessReadRequest;
use App\Services;

class CommentController extends Controller
{
    protected $annotationService;
    protected $commentService;

    public function __construct(Services\Annotations $annotationService, Services\Comments $commentService)
    {
        $this->annotationService = $annotationService;
        $this->commentService = $commentService;
        // TODO: doesn't work post 5.1, use the proper way
        // $this->beforeFilter('auth', ['on' => ['post', 'put', 'delete']]);
    }

    public function getIndex(DocAccessReadRequest $request, Doc $doc)
    {
        $excludeUserIds = [];
        if ($request->query('exclude_sponsors') && $request->query('exclude_sponsors') !== 'false') {
            $excludeUserIds = $doc->sponsorIds;
        }

        $comments = new Collection();
        if ($request->query('parent_id')) {
            $commentsQuery = Annotation
                ::where('annotatable_type', Annotation::ANNOTATABLE_TYPE)
                ->where('annotatable_id', $request->query('parent_id'))
                ->where('annotation_type_type', Annotation::TYPE_COMMENT)
                ;
        } elseif ($request->query('all') && $request->query('all') !== 'false') {
            $commentsQuery = $doc->allComments();
        } else {
            $commentsQuery = $doc
                ->comments()
            ;
        }

        $commentsQuery
            ->whereNotIn('user_id', $excludeUserIds)
            ;

        if ($request->query('only_notes') && $request->query('only_notes') !== 'false') {
            $commentsQuery->onlyNotes();
        }

        if ($request->query('exclude_notes') && $request->query('exclude_notes') !== 'false') {
            $commentsQuery->notNotes();
        }

        $comments = $commentsQuery->get();

        // a little silly, we should probably support a more general
        // download=true param and a content type headers, but for now we'll
        // just do this because that's how it has been and the returned data
        // isn't exactly the same between the json and csv
        if ($request->query('download') === 'csv') {
            $csv = $this->commentService->toCsv($comments);
            $csv->output('comments.csv');
            return;
        } else {
            $includeReplies = !$request->exists('include_replies') || $request->query('include_replies') && $request->query('include_replies') !== 'false';
            $results = $comments->map(function ($item) use ($includeReplies) {
                return $this->toAnnotatorArray($item, $includeReplies);
            });

            return Response::json($results);
        }

    }

    public function getComment(DocAccessReadRequest $request, Doc $doc, Annotation $comment)
    {
        if (!($comment->rootAnnotatable instanceof Doc) || $comment->rootAnnotatable->id != $doc->id) {
            App::abort(404, 'A comment with id "'.$comment->id.'" does not exist on document with id "'.$doc->id);
        }

        return Response::json($this->toAnnotatorArray($comment));
    }

    public function postIndex(DocAccessReadRequest $request, Doc $doc)
    {
        return $this->createComment($doc, Auth::user(), $request->all());
    }

    public function postSeen(DocAccessEditRequest $request, Doc $doc, Annotation $comment)
    {
        $allowed = false;

        $user = Auth::user();

        $this->annotationService->createAnnotationSeen($comment, $user, []);

        Event::fire(new FeedbackSeen($comment, $user));

        return Response::json($this->toAnnotatorArray($comment));
    }

    public function postLikes(DocAccessReadRequest $request, Doc $doc, Annotation $comment)
    {
        $user = Auth::user();

        if ($comment->likes()->where('user_id', $user->id)->count()) {
            return Response::json($this->toAnnotatorArray($comment));
        }

        $this->annotationService->createAnnotationLike($comment, $user, []);

        return Response::json($this->toAnnotatorArray($comment));
    }

    public function postFlags(DocAccessReadRequest $request, Doc $doc, Annotation $comment)
    {
        $user = Auth::user();

        if ($comment->flags()->where('user_id', $user->id)->count()) {
            return Response::json($this->toAnnotatorArray($comment));
        }

        $this->annotationService->createAnnotationFlag($comment, $user, []);

        return Response::json($this->toAnnotatorArray($comment));
    }

    public function postComments(DocAccessReadRequest $request, Doc $doc, Annotation $comment)
    {
        return $this->createComment($comment, Auth::user(), $request->all());
    }

    public function createComment($target, $user, $data)
    {
        $newComment = $this->createFromAnnotatorArray($target, $user, $data);

        Event::fire(new CommentCreated($newComment, $target));

        return Response::json($this->toAnnotatorArray($newComment));
    }

    public function createFromAnnotatorArray($target, $user, array $data)
    {
        $isEdit = false;
        //Check for edit tag
        if (!empty($data['tags']) && in_array('edit', $data['tags'])) {
            $isEdit = true;

            //If no explanation present, throw error
            if (!isset($data['explanation'])) {
                throw new \Exception('Explanation required for edits');
            }
        }

        $id = DB::transaction(function () use ($target, $user, $data, $isEdit) {
            if ((!empty($data['ranges']) && $target instanceof Doc)
                || (empty($data['ranges']) && $target instanceof Annotation && $target->isNote())
            ) {
                $data['subtype'] = Annotation::SUBTYPE_NOTE;
            }

            $annotation = $this->annotationService->createAnnotationComment($target, $user, $data);

            $permissions = new AnnotationPermission();
            $permissions->annotation_id = $annotation->id;
            $permissions->user_id = $user->id;
            $permissions->read = 1;
            $permissions->update = 0;
            $permissions->delete = 0;
            $permissions->admin = 0;
            $permissions->save();

            if (!empty($data['ranges'])) {
                foreach ($data['ranges'] as $range) {
                    $this->annotationService->createAnnotationRange($annotation, $user, $range);
                }
            }

            if (!empty($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    $this->annotationService->createAnnotationTag($annotation, $user, ['tag' => $tag]);
                }
            }

            if ($isEdit) {
                $editData = [
                    'text' => $data['explanation'],
                    'subtype' => !empty($data['subtype']) ? $data['subtype'] : null,
                ];
                $this->annotationService->createAnnotationComment($annotation, $user, $editData);
            }

            return $annotation->id;
        });

        return Annotation::find($id);
    }

    public function toAnnotatorArray(Annotation $comment, $includeChildren = true, $userId = null)
    {
        if ($comment->annotation_type_type !== Annotation::TYPE_COMMENT) {
            throw new InvalidArgumentException('Can only handle Annotations of type Comment');
        }

        $getUserInfo = function (User $user) {
            return array_intersect_key($user->toArray(), array_flip(['id', 'email', 'display_name']));
        };

        $item['id'] = $comment->id;
        $item['annotator_schema_version'] = 'v1.0';
        $item['ranges'] = [];
        $item['tags'] = [];
        $item['comments'] = [];
        $item['permissions'] = [
            'read' => [],
            'update' => [],
            'delete' => [],
            'admin' => [],
        ];

        $item['text'] = $comment->annotationType->content;

        if ($includeChildren) {
            $childComments = $comment->comments;
            foreach ($childComments as $childComment) {
                $item['comments'][] = [
                    'id' => $childComment->id,
                    'text' => $childComment->annotationType->content,
                    'created_at' => $childComment->created_at->toRfc3339String(),
                    'updated_at' => $childComment->updated_at->toRfc3339String(),
                    'user' => $getUserInfo($childComment->user),
                    'likes' => $childComment->likes_count,
                    'flags' => $childComment->flags_count,
                ];
            }
        } else {
            $item['comments_count'] = $comment->comments_count;
        }

        $ranges = $comment->ranges;
        foreach ($ranges as $range) {
            $rangeData = $range->annotationType;
            $item['ranges'][] = [
                'start' => $rangeData->start,
                'end' => $rangeData->end,
                'startOffset' => $rangeData->start_offset,
                'endOffset' => $rangeData->end_offset,
            ];
        }

        $item['user'] = $getUserInfo($comment->user);

        $item['consumer'] = Annotation::ANNOTATION_CONSUMER;

        foreach ($comment->tags as $tag) {
            $item['tags'][] = $tag->annotationType->tag;
        }

        $permissions = AnnotationPermission::where('annotation_id', '=', $comment->id)->get();
        foreach ($permissions as $perm) {
            if ($perm->read) {
                $item['permissions']['read'][] = $perm['user_id'];
            }

            $item['permissions']['update'][] = $perm->update ? $perm['user_id'] : '0';
            $item['permissions']['delete'][] = $perm->update ? $perm['user_id'] : '0';
            $item['permissions']['admin'][] = $perm->admin ? $perm['user_id'] : '0';
        }

        $item['likes'] = $comment->likes_count;
        $item['flags'] = $comment->flags_count;
        $item['seen'] = (bool) $comment->seens_count;
        $item['created_at'] = $comment->created_at->toRfc3339String();
        $item['updated_at'] = $comment->updated_at->toRfc3339String();

        // Pull in all other data
        if ($comment->data) {
            $item = array_merge($item, $comment->data);
        }

        // Filter down to just the keys we should send, just to be safe
        $item = array_intersect_key($item, array_flip([
            'id', 'annotator_schema_version', 'created_at', 'updated_at',
            'text', 'quote', 'uri', 'ranges', 'user', 'consumer', 'tags',
            'permissions', 'likes', 'flags', 'seen', 'comments',
            'comments_count', 'doc_id',
        ]));

        return $item;
    }
}
