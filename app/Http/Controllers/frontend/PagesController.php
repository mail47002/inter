<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Page;

class PagesController extends Controller
{
	public function index($slug)
	{
        $page = Page::where('slug', $slug)->first();

        if ($page) {
            return view('frontend.pages.index', [
                'page' => $page
            ]);
        } else {
            return view('frontend.pages.404');
        }
	}

}
