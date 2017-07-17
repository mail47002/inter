<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;

class PagesController extends Controller
{
	public function index($page = 'home')
	{
		switch ($page) {
			case 'naudojimosi-taisykles':
				$page = 'rules';
			break;
			case 'duk':
				$page = 'faq';
			break;
			case 'kontaktai':
				$page = 'contacts';
			break;
		}

		try {
			return view('frontend.pages.' . $page);
		} catch (Exception $e) {
			return view('frontend.pages.404');
		}
	}

}
