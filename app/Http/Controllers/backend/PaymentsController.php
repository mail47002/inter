<?php

namespace App\Http\Controllers\backend;

use App\Payment;
use App\Http\Controllers\Controller;

class PaymentsController extends Controller
{

	function __construct()
	{
        $this->middleware('admin');
	}

	public function index()
	{
		$entries = Payment::orderBy('updated_at', 'desc')->paginate(10);

		return view('backend.payments.index')
			->withEntries($entries);
	}

}