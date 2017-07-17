<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use File;
use Image;

class AccountController extends Controller
{
    // Validation messages
    protected $messages = [
        'required'		=> 'Būtina užpildyti',
        'enough'		=> 'Neužtenka kreditų',
        'min'			=> 'Reikškmė turi susidaryti bent iš :min simbolių',
        'mimes'			=> 'Netinkamas formatas. Galimi formatai: <em>jpeg, gif, bmp, png</em>.',
        'confirmed'		=> 'Slaptažodžiai nesutampa',
        'unique'		=> 'Jau egizstuoja',
    ];

	function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('frontend.account.index');
	}

	public function update(Request $request)
	{
		$validation = Validator::make($request->all(), array(
			'username' 				=> 'required|unique:users,username,' . Auth::user()->id,
			'password' 				=> 'confirmed|min:6',
			'photo' 				=> 'mimes:jpeg,gif,bmp,png',
		), $this->messages);

		if ($validation->fails()) {
			return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
		} else {
			$entry = Auth::user();

			$entry->username = $request->username;

			if ($request->has('password')) {
                $entry->password = Hash::make((string)$request->password);
            }

			// Uploading photo
			if ($request->hasFile('photo')) {
				$file = $request->file('photo');

				if ($file->isValid()) {
					$path 	= 'uploads/users/photos/' . base64_encode($entry->id) . '/';
					$name	= $file->getClientOriginalName();

					// deleting old if exists
					File::deleteDirectory($path);

					// creating directories
					File::makeDirectory($path . 'default', 0777, true, true);

					// Save 
					$file->move($path . 'default/', $name);

					// Resize if needed
					if (Image::make($path . 'default/' . $name)->width() > 200)
						Image::make($path . 'default/' . $name)->widen(200)->save();

					// Assign images
					$entry->photo = $path . 'default/' . $name;
				}
			}

			$entry->save();

			return redirect()
                ->route('account.index')
                ->withUpdated(1);
		}
	}

}
