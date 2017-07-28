<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Payment;
use App\Services\WebToPay;

class PaymentsController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth', [
	        'except' => [
                'callback','success', 'cancel'
            ]
        ]);
	}

	public function create($amount)
	{

	}

	public function callback(Request $request)
	{

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
