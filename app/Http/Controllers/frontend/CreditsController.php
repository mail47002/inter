<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Setting;

class CreditsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('frontend.credits.index');
	}

}