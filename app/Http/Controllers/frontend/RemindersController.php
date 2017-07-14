<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;

class RemindersController extends Controller
{

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return view('frontend.password.remind');
	}

	public function success()
	{
		return view('frontend.password.success');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind(Request $request)
	{
		$response = Password::remind(['email' => $request->only('email')], function($message) {
		    $message->subject('Slaptažodžio priminimas');
		});

		switch ($response)
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get($response));
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) {
		    abort(404);
        }

		return view('frontend.password.reset', [
            'token'     => $token
        ]);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset(Request $request)
	{
		$credentials = $request->only('email', 'password', 'password_confirmation', 'token');

		$response = Password::reset($credentials, function($user, $password) {
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response) {
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::route('password.success');
		}
	}

}
