<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    public function index()
    {
        return view('backend.login.index');
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        if ($validation->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
        }

        if (Auth::attempt($request->only('email', 'password')) && Auth::user()->isAdmin()) {
            $request->session()->regenerate();

            return redirect()
                ->route('pages.index')
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
