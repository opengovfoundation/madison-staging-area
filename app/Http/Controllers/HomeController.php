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
        $page = $request->input('page', 1);

        $documents = Doc::getEager()
            ->where('publish_state', 'published')
            ->paginate(5);

        $featuredDocuments = Doc::getFeatured();
        $mostActiveDocuments = Doc::getActive(6);
        $mostRecentDocuments = Doc::sixMostRecent()->get();

        return view('home', [
            'documents' => $documents,
            'featuredDocuments' => $featuredDocuments,
            'mostActiveDocuments' => $mostActiveDocuments,
            'mostRecentDocuments' => $mostRecentDocuments,
        ]);
    }
}
