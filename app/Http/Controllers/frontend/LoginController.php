<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\UserCredit;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;

class LoginController extends Controller
{

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
		$this->validate($request, [
			'r_email' 				    => 'required|email|unique:users,email',
			'r_username' 			    => 'required|unique:users,username',
			'r_password' 			    => 'required|confirmed|min:5',
			'r_password_confirmation'   => 'required',
		]);

        $entry = new User;

        $entry->role 	    = config('users.role.user');
        $entry->email 	    = $request->r_email;
        $entry->username    = $request->r_username;
        $entry->password    = Hash::make((string)$request->r_password);

        $entry->save();

        $entry_credits = new UserCredit;

        $entry_credits->user_id     = $entry->id;
        $entry_credits->credits     = config('users.credits');
        $entry_credits->description = '';

        $entry_credits->save();

        return redirect()
            ->route('home')
            ->withRegistered(1);
	}

	public function registerFacebook(Request $request)
	{

	}

	public function registerGoogle(Request $request)
	{

	}

	public function login(Request $request)
	{
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

		if (Auth::guard('web')->attempt($this->credentials($request))) {
			return redirect()
                ->route('campaigns.my')
                ->withLogned(1);
		}

        return redirect()
            ->back()
            ->withLogin_error(1)
            ->withInput();
	}

	public function logout()
	{
        Auth::guard('web')->logout();

		return redirect()->route('home')->withLogout(1);
	}

	protected function credentials(Request $request)
    {
        return [
            'email'     => $request->email,
            'password'  => $request->password,
            'status'    => 1
        ];
    }
}