<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document as Requests;
use App\Models\Doc as Document;
use App\Models\DocContent as DocumentContent;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: filter to only docs user has access to or otherwise more
        // restricted sets:
        // - Documents created by user
        // - Documents user can see (so group docs or all if admin)
        $documents = Document::paginate(15);
        return view('documents.list', ['documents' => $documents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create');
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

        $doc = new Document();
        $doc->title = $title;
        $doc->slug = $slug;
        $doc->save();

        // TODO: set the proper group_id for sponsorship in form
        $doc->sponsors()->sync([$request->input('group_id')]);

        $starter = new DocumentContent();
        $starter->doc_id = $doc->id;
        $starter->content = "New Document Content";
        $starter->save();

        $doc->init_section = $starter->id;
        $doc->save();

        flash(trans('messages.document_created'));
        return redirect()->route('documents.edit', ['document' => $doc->id]);
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
        //
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
