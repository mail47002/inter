<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;
use Log;

class PaymentsController extends Controller
{
	public function __construct()
	{

	}

	public function callback(Request $request)
	{
        Log::info(json_encode($request->all()));
	}

	public function success()
	{
		return view('frontend.payments.success');
	}

	public function cancel()
	{
		return view('frontend.payments.cancel');
	}

}
