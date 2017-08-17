<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;
use App\Services\PaypalPDT;
use Log;

class PaymentsController extends Controller
{
	public function __construct()
	{

	}

	public function success(Request $request)
	{
        $pdt = new PaypalPDT();

        $pdt->useSandbox();

        $verified = $pdt->verify($request);

        if ($verified) {
            dd($pdt->getPaymentData());
        }

		return view('frontend.payments.success');
	}

	public function cancel()
	{
		return view('frontend.payments.cancel');
	}

}
