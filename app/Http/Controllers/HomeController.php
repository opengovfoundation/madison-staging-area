<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doc;
use App\Models\Category;

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
        $search = $request->input('search');
        $categories = $request->input('categories');

        $selectedCategories = [];

        $documentQuery = Doc::getEager()->where('publish_state', 'published');

        if ($search) {
            $documentQuery->where('title', 'like', '%' . $search . '%');
        }

        if ($categories) {
            $selectedCategories = Category::whereIn('id', explode(',', $categories))->get();
            $documentQuery->whereHas('categories', function($q) use ($categories) {
                $q->whereIn('id', explode(',', $categories));
            });
        }

        $documents = $documentQuery->paginate(5);
        $featuredDocuments = Doc::getFeatured();
        $mostActiveDocuments = Doc::getActive(6);
        $mostRecentDocuments = Doc::sixMostRecent();

        return view('home', compact(
            'selectedCategories',
            'documents',
            'featuredDocuments',
            'mostActiveDocuments',
            'mostRecentDocuments'
        ));
    }
}
