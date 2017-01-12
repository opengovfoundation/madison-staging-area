<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Http\Requests\Document\View as DocumentViewRequest;
use App\Models\Annotation;
use App\Models\AnnotationPermission;
use App\Models\Doc as Document;
use App\Models\User;
use App\Services;
use DB;
use Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Response;

class CommentController extends Controller
{
    protected $annotationService;
    protected $commentService;

    public function __construct(Services\Annotations $annotationService, Services\Comments $commentService)
    {
        $this->annotationService = $annotationService;
        $this->commentService = $commentService;

        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DocumentViewRequest $request, Document $document)
    {
        $excludeUserIds = [];
        if ($request->query('exclude_sponsors') && $request->query('exclude_sponsors') !== 'false') {
            $excludeUserIds = $document->sponsorIds;
        }

        $comments = new Collection();
        if ($request->query('parent_id')) {
            $commentsQuery = Annotation
                ::where('annotatable_type', Annotation::ANNOTATABLE_TYPE)
                ->where('annotatable_id', $request->query('parent_id'))
                ->where('annotation_type_type', Annotation::TYPE_COMMENT)
                ;
        } elseif ($request->query('all') && $request->query('all') !== 'false') {
            $commentsQuery = $document->allComments();
        } else {
            $commentsQuery = $document
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
        } elseif ($request->expectsJson()) {
            $includeReplies = !$request->exists('include_replies') || $request->query('include_replies') && $request->query('include_replies') !== 'false';
            $results = $comments->map(function ($item) use ($includeReplies) {
                return $this->toAnnotatorArray($item, $includeReplies);
            });

            return Response::json($results);
        } else {
            // TODO: html view?
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentViewRequest $request, Document $document)
    {
        return $this->createComment($document, $request->user(), $request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO
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
        // check for edit tag
        if (!empty($data['tags']) && in_array('edit', $data['tags'])) {
            $isEdit = true;

            // if no explanation present, throw error
            if (!isset($data['explanation'])) {
                throw new \Exception('Explanation required for edits');
            }
        }

        $id = DB::transaction(function () use ($target, $user, $data, $isEdit) {
            if ((!empty($data['ranges']) && $target instanceof Document)
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
                    'created_at_relative' => $childComment->created_at->diffForHumans(),
                    'updated_at' => $childComment->updated_at->toRfc3339String(),
                    'updated_at_relative' => $childComment->updated_at->diffForHumans(),
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
        $item['created_at_relative'] = $comment->created_at->diffForHumans();
        $item['updated_at'] = $comment->updated_at->toRfc3339String();
        $item['updated_at_relative'] = $comment->updated_at->diffForHumans();

        // Pull in all other data
        if ($comment->data) {
            $item = array_merge($item, $comment->data);
        }

        // Filter down to just the keys we should send, just to be safe
        $item = array_intersect_key($item, array_flip([
            'id', 'annotator_schema_version', 'created_at',
            'created_at_relative', 'updated_at', 'updated_at_relative',
            'text', 'quote', 'uri', 'ranges', 'user', 'consumer', 'tags',
            'permissions', 'likes', 'flags', 'seen', 'comments',
            'comments_count', 'doc_id',
        ]));

        return $item;
    }
}
