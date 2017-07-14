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
    // View folder
    protected $view = 'login';

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
		return view('frontend.' . $this->view . '.index');
	}

	public function registration()
	{
		return view('frontend.' . $this->view . '.register');
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
		$code = Input::get( 'code' );

	    $fb = OAuth::consumer( 'Facebook' );

	    if ( ! empty( $code ) )
	    {
			$token = $fb->requestAccessToken( $code );

			$result = json_decode( $fb->request( '/me' ), true );

			$id 		= $result['id'];
		    $email 		= $result['email'];
		    $username 	= $result['name'];

		    // check if users exists
		    $user_network = UserNetwork::where('social_id', '=', $id)->where('type', '=', Config::get('social_types.facebook'))->first();

		    if ($user_network)
		    {
		    	Auth::loginUsingId( $user_network->user_id );

		    	return Redirect::route('campaigns.my')->withLogned_social(Config::get('social_types.facebook'));
		    }
		    else
		    {
		    	$user = User::where('email', '=', $email)->first();

		    	if ( ! $user)
		    	{
			    	$password = time() + rand(1, 100);

			    	// creating user
			    	$user = new User;

			    	$user->email = $email;
			    	$user->username = $username;
			    	$user->type = Config::get('user_types.user');
			    	$user->password = Hash::make((string)$password);

			    	$user->save();

			    	// sending new password
			    	$data['password'] 	= $password;
			    	$data['email'] 		= $email;

			    	Mail::send('emails.login.new_password', $data, function($message) use ($data)
					{
					    $message->from('info@apklausos.lt', 'Apklausos');
					    $message->subject('Prisijungimo duomenys');

					    $message->to($data['email']);
					});
		    	}

		    	// adding user network
		    	$network = new UserNetwork;

		    	$network->user_id = $user->id;
		    	$network->type = Config::get('social_types.facebook');
		    	$network->social_id = $id;

		    	$network->save();

		    	Auth::loginUsingId( $user->id );

		    	return Redirect::route('campaigns.my')->withLogned_social(Config::get('social_types.facebook'));
		    }
	    }
	    else
		{
			$url = $fb->getAuthorizationUri();

			return Redirect::to( (string)$url );
		}
	}

	public function registerGoogle(Request $request)
	{
	    $code = $request->code;

	    $googleService = OAuth::consumer('Google');

	    if (!empty($code)) {
	        $token = $googleService->requestAccessToken($code);

	        $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true );

	        $id 		= $result['id'];
		    $email 		= $result['email'];
		    $username 	= $result['given_name'] . ' ' . $result['family_name'];

		    // check if users exists
		    $user_network = UserNetwork::where('social_id', '=', $id)
                ->where('type', '=', config('social_types.google'))
                ->first();

		    if ($user_network) {
		    	Auth::loginUsingId($user_network->user_id);

		    	return redirect()
                    ->route('campaigns.my')
                    ->withLogned_social(config('social_types.google'));
		    } else {
		    	$user = User::where('email', '=', $email)->first();

		    	if (!$user) {
			    	$password = time() + rand(1, 100);

			    	// creating user
			    	$user = new User;

			    	$user->email = $email;
			    	$user->username = $username;
			    	$user->type = Config::get('user_types.user');
			    	$user->password = Hash::make((string)$password);

			    	$user->save();

			    	// sending new password
			    	$data['password'] 	= $password;
			    	$data['email'] 		= $email;

			    	Mail::send('emails.login.new_password', $data, function($message) use ($data)
					{
					    $message->from('info@apklausos.lt', 'Apklausos');
					    $message->subject('Prisijungimo duomenys');

					    $message->to($data['email']);
					});
		    	}

		    	// adding user network
		    	$network = new UserNetwork;

		    	$network->user_id = $user->id;
		    	$network->type = Config::get('social_types.google');
		    	$network->social_id = $id;

		    	$network->save();

		    	Auth::loginUsingId( $user->id );

		    	return Redirect::route('campaigns.my')->withLogned_social(Config::get('social_types.google'));
		    }
	    }
	    else
	    {
	        $url = $googleService->getAuthorizationUri();

	        return Redirect::to( (string)$url );
	    }
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