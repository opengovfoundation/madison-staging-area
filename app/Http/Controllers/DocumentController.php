<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document as Requests;
use App\Models\Category;
use App\Models\Doc as Document;
use App\Models\DocContent as DocumentContent;
use App\Models\Group;
use Auth;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\Index $request)
    {
        $orderField = $request->input('order', 'updated_at');
        $orderDir = $request->input('order_dir', 'DESC');
        $discussionStates = $request->input('discussion_state', null);

        $documentsQuery = Document
            ::orderby($orderField, $orderDir)
            ->where('is_template', '!=', '1');

        if ($discussionStates) {
            $doc->whereIn('discussion_state', $discussionStates);
        }

        // TODO: the publish state and group id stuff needs some more work,
        // default behavior should be to filter to only documents that are
        // public or the user owns (i.e., they are a member of the group that
        // owns them with sufficient privileges to view the document in it's
        // current state)
        //
        // If the user specifies publish states and no groups, view all
        // published documents (if that was a publish state requested) and the
        // publish states allowed for each group the user belongs to
        //
        // If the user specifies some groups but no explicit publish states,
        // should show every document visible to the user in those groups, for
        // some they might be able to see all the publish states, for others
        // maybe only published

        $publishStates = $request->input('publish_state', [Document::PUBLISH_STATE_PUBLISHED]);
        if (in_array('all', $publishStates) || !in_array(Document::PUBLISH_STATE_PUBLISHED, $publishStates)) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                return abort(403, 'Unauthorized.');
            }
        } else {
            $documentsQuery->where('publish_state', '=', $publishStates);
        }

        if ($request->has('group_id') && !in_array('any', $request->input('group_id'))) {
            $documentsQuery->whereHas('sponsors', function ($q) use ($request) {
                $groupIds = $request->input('group_id');
                $q->whereIn('id', $groupIds);
            });
        }

        if ($request->has('category_id') && !in_array('any', $request->input('category_id'))) {
            $documentsQuery->whereHas('categories', function ($q) use ($request) {
                $ids = $request->input('category_id');
                $q->whereIn('categories.id', $ids);
            });
        } elseif ($request->has('category')) {
            $documentsQuery->whereHas('categories', function ($q) use ($request) {
                $category = $request->input('category');
                $q->where('categories.name', 'LIKE', "%$category%");
            });
        }

        if ($request->has('title')) {
            $title = $request->get('title');
            $documentsQuery->where('title', 'LIKE', "%$title%");
        }

        $documents = $documentsQuery->paginate($request->input('limit', 10));

        $categories = Category::all();
        $groups = Group::all();
        $publishStates = Document::validPublishStates();
        $discussionStates = Document::validDiscussionStates();
        return view('documents.list', compact('documents', 'categories', 'groups', 'publishStates', 'discussionStates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $availableGroups = $user->groups;
        $availableGroups->filter(function ($group) use ($user) {
            return $group->userCanCreateDocument($user);
        });

        $activeGroup = $request->user()->activeGroup();
        if ($activeGroup && !$activeGroup->userCanCreateDocument($user)) {
            $activeGroup = null;
        }

        return view('documents.create', compact('availableGroups', 'activeGroup'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Store $request)
    {
        $title = $request->input('title');
        $slug = $request->input('slug', str_slug($title, '-'));

        // If the slug is taken
        if (Document::where('slug', $slug)->count()) {
            $counter = 0;
            $tooMany = 10;
            do {
                if ($counter > $tooMany) {
                    flash(trans('messages.document_title_invalid'));
                    return back()->withInput();
                }
                $counter++;
                $new_slug = $slug . '-' . str_random(8);
            } while (Document::where('slug', $new_slug)->count());

            $slug = $new_slug;
        }

        $document = new Document();
        $document->title = $title;
        $document->slug = $slug;
        $document->save();

        $document->sponsors()->sync([$request->input('group_id')]);

        $starter = new DocumentContent();
        $starter->doc_id = $document->id;
        $starter->content = "New Document Content";
        $starter->save();

        $document->init_section = $starter->id;
        $document->save();

        flash(trans('messages.document_created'));
        return redirect()->route('documents.edit', ['document' => $document->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO: document page
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\Edit $request, Document $document)
    {
        return view('documents.edit', ['document' => $document]);
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
        // TODO: implement
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\Edit $request, Document $document)
    {
        if ($request->user()->isAdmin()) {
            $document->publish_state = Document::PUBLISH_STATE_DELETED_ADMIN;
        } else {
            $document->publish_state = Document::PUBLISH_STATE_DELETED_USER;
        }

        $document->save();

        $document->annotations()->delete();
        $document->doc_meta()->delete();
        $document->content()->delete();

        $document->delete();

        flash(trans('messages.document_deleted'));
        return redirect()->route('documents.index');
    }
}
