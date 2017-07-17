<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserNetwork;
use Validator;
use Hash;
use Auth;

class LoginController extends Controller
{
    // Validation messages
    protected $messages = [
        'required'		=> 'Laukelis yra privalomas',
        'email'			=> 'Klaidingas el. pašto adresas',
        'unique'		=> 'Jau egzistruoja',
        'confirmed'		=> 'Nesutampa',
        'min'			=> 'Per trumpas (min. :min simboliai)',
    ];

	public function index()
	{
		return view('frontend.login.index');
	}

	public function registration()
	{
		return view('frontend.login.register');
	}

	public function register(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'r_email' 				    => 'required|email|unique:users,email',
			'r_username' 			    => 'required|unique:users,username',
			'r_password' 			    => 'required|confirmed|min:5',
			'r_password_confirmation'   => 'required',
		], $this->messages);

		if ($validation->fails()) {
			return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
		} else {
            $entry = new User;

            $entry->type 	    = config('user_types.user');
            $entry->email 	    = $request->r_email;
            $entry->username    = $request->r_username;
            $entry->password    = Hash::make((string)$request->r_password);

            $entry->save();

			return redirect()
                ->route('home')
                ->withRegistered(1);
		}
	}

	public function registerFacebook(Request $request)
	{

	}

	public function registerGoogle(Request $request)
	{

	}

	public function login(Request $request)
	{
        $validation = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string',
        ], $this->messages);

        if ($validation->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
        }

		if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

			return redirect()
                ->route('campaigns.my')
                ->withLogned(1);
		} else {
			return redirect()
                ->back()
                ->withLogin_error(1)
                ->withInput();
		}
	}

	public function logout(Request $request)
	{
        Auth::logout();

        $request->session()->flush();

        $request->session()->regenerate();

		return redirect()->route('home')->withLogout(1);
	}

}