<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\UserRegister;
use App\UserCredit;
use Illuminate\Http\Request;
use App\User;
use App\UserNetwork;
use Hash;
use Auth;
use Socialite;
use Exception;

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
			'email' 				    => 'required|email|unique:users,email',
			'username' 			        => 'required|unique:users,username',
			'password' 			        => 'required|confirmed|min:5',
			'password_confirmation'     => 'required',
		]);

        $this->create($request->all());

        return redirect()
            ->route('home')
            ->withRegistered(1);
	}

	public function redirectToFacebook()
	{
        return Socialite::driver('facebook')->redirect();
	}

    public function  handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            $entry_network = UserNetwork::where('social_id', $user->id)->first();

            if (!$entry_network) {
                $password = $this->makePassword();

                $entry = $this->create([
                    'email' 	    => $user->email,
                    'username'      => $user->name,
                    'password'      => $password
                ]);

                Mail::to($user->name)->send(new UserRegister($entry, $password));
            }

            Auth::loginUsingId($entry->user_id);

            return redirect()->route('campaigns.my');
        } catch (Exception $e) {
            return redirect()->route('login.register_facebook');
        }
    }

	public function redirectToGoogle()
	{
        return Socialite::driver('google')->redirect();
	}

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $entry_network = UserNetwork::where('social_id', $user->id)->first();

            if (!$entry_network) {
                $password = $this->makePassword();

                $entry = $this->create([
                    'email' 	    => $user->email,
                    'username'      => $user->name,
                    'password'      => $password
                ]);

                Mail::to($user->name)->send(new UserRegister($entry, $password));
            }

            Auth::loginUsingId($entry->user_id);

            return redirect()->route('campaigns.my');
        } catch (Exception $e) {
            return redirect()->route('login.register_google');
        }
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

    private function create($data)
    {
        $entry = User::create([
            'role' 	        => config('users.role.user'),
            'email' 	    => $data['email'],
            'username'      => $data['username'],
            'password'      => Hash::make((string)$data['password'])
        ]);

        UserCredit::create([
            'user_id'       => $entry->id,
            'credits'       => config('settings.registration_credits'),
            'description'   => 'Įskaitymas'
        ]);

        return $entry;
    }

    private function makePassword()
    {
        return time() + rand(1, 100);
    }

}