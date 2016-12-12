<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doc;

use Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 5);
        $page = $request->input('page', 1);

        $documents = Doc::getEager()
            ->where('publish_state', 'published')
            ->take($limit)
            ->skip($limit * ($page - 1))
            ->get();

        $featuredDocuments = Doc::getFeatured();

        $mostActiveDocuments = Doc::getActive(6);

        $mostRecentDocuments = Doc::getEager()
            ->orderBy('updated_at', 'DESC')
            ->where('discussion_state', 'open')
            ->where('publish_state', 'published')
            ->where('is_template', '!=', '1')
            ->take(6)
            ->get();

        return view('home', [
            'documents' => $documents,
            'featuredDocuments' => $featuredDocuments,
            'mostActiveDocuments' => $mostActiveDocuments,
            'mostRecentDocuments' => $mostRecentDocuments,
        ]);
    }
}
