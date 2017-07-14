<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Payment;
use App\Services\WebToPay;

class PaymentsController extends Controller
{
	private $projectId = '55780';
	private $password = 'aa9abedcb245985b4d98dbc804e7bc7a';

    // View folder
	protected $view = 'payments';

	function __construct()
	{
//		$except = ['callback', 'success', 'cancel'];
//
//		$this->beforeFilter('auth', ['except' => $except]);

	}

	public function create($ammount)
	{
		$allow = 0;

		foreach (config('prices') as $price) {
    		if ($price['ammount'] == $ammount) {
                $allow = 1;
            }
    	}

    	if ($allow == 0) {
            exit('Error');
        }

		$payment = new Payment;

		$payment->user_id = Auth::user()->id;
		$payment->ammount = $ammount;

		$payment->save();

		WebToPay::redirectToPayment(array(
	        'projectid'     => $this->projectId,
	        'sign_password' => $this->password,
	        'orderid'       => $payment->id,
	        'amount'        => $payment->ammount * 100,
	        'currency'      => 'LTL',
	        'country'       => 'LT',
	        'accepturl'     => route('payments.success'),
	        'cancelurl'     => route('payments.cancel'),
	        'callbackurl'   => route('payments.callback'),
	        'test'          => 1,
	    ));
	}

	public function callback(Request $request)
	{
		$response = WebToPay::checkResponse($request->all(), [
	        'projectid'     => $this->projectId,
	        'sign_password' => $this->password,
	    ]);
	 
	    $paymentId 	= $response['orderid'];
	    $amount 	= $response['amount'];

	    $payment = Payment::find($paymentId);

	    if ($payment && $payment->paid == 0 && $payment->ammount == ($amount / 100)) {
	    	// ammount to credits
	    	$credits = 0;

	    	foreach (config('prices') as $price) {
	    		if ($price['ammount'] == $payment->ammount) {
                    $credits = $price['credits'];
                }
	    	}

	    	if (!$credits) {
                echo 'Invalid ammount';
            }
	    	
	    	// Mark payment as paid
	    	$payment->paid = 1;
	    	$payment->save();

	    	// adding credits
	    	$credit = new UserCredit;

	    	$credit->user_id 		= $payment->user_id;
	    	$credit->credits 		= $credits;
	    	$credit->description 	= 'Įsigyta internetu';

	    	$credit->save();

	    	echo 'OK';
	    } else {
	    	echo 'BAD';
	    }
	}

	public function success()
	{
		return View::make('frontend.' . $this->view . '.success');
	}

	public function cancel()
	{
		return View::make('frontend.' . $this->view . '.cancel');
	}

}
