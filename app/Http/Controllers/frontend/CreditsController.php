<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;

class CreditsController extends Controller
{
    // View folder
    protected $view = 'credits';

	function __construct()
	{
//		$this->beforeFilter('auth');
	}

	public function index()
	{
		return view('frontend.' . $this->view . '.index', [
		    'prices' => config('prices')
        ]);
	}

}