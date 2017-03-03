<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doc as Document;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $featuredDocuments = Document::getFeaturedOrRecent();
        $popularDocuments = Document::getActive(3);

        return view('home', compact([
            'selectedCategories',
            'featuredDocuments',
            'popularDocuments',
        ]));
    }
}
