<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;
use App\UserCredit;
use Auth;
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

    if ($verified && Auth::check()) {
    	// dd($pdt->getPaymentData());
    	$payment_data = $pdt->getPaymentData();
    	$entry = Auth::user();

    	$payment = new Payment();

    	$payment->user_id = $entry->id;
    	$payment->ammount = $payment_data['mc_gross'];
    	$payment->currency = $payment_data['mc_currency'];
    	$payment->paid = $payment_data['txn_id'];;

    	if ($payment->save()) {
    		$credits = new UserCredit();
    		$credits->user_id = $entry->id;
    		$credits->credits = $payment_data['mc_gross'] * config('settings.one_credits');
    		$credits->description = 'Pirkti internetu';
    		$credits->save();
    	}
    	return view('frontend.payments.success');
    }
	return view('frontend.payments.cancel');

	}

	public function cancel()
	{
		return view('frontend.payments.cancel');
	}

}
