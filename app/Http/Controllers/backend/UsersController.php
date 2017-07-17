<?php

namespace App\Http\Controllers\frontend;

use App\User;
use Illuminate\Http\Request;
use Validator;
use File;
use Image;
use App\Http\Controllers\Controller;


class UsersController extends Controller
{
    protected $view = 'users';

    protected $messages = [
        'required'		=> 'Būtina užpildyti',
        'email'			=> 'Netinkamas el. pašto adresas',
        'enough'		=> 'Neužtenka kreditų',
        'min'			=> 'Reikškmė turi susidaryti bent iš :min simbolių',
        'mimes'			=> 'Netinkamas formatas. Galimi formatai: <em>jpeg, gif, bmp, png</em>.',
        'confirmed'		=> 'Slaptažodžiai nesutampa',
        'unique'		=> 'Jau egizstuoja',
    ];

	public function __construct()
	{
	    $this->middleware('auth');
	}

	public function index()
	{
		$entries = User::orderBy('type', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

		return view('backend.' . $this->view . '.index')
			->withEntries($entries);
	}

	public function create()
	{
		return view('backend.' . $this->view . '.create');
	}

	public function store(Request $request)
	{
		$validation = Validator::make($request->all(), array(
			'email' 				=> 'required|email|unique:users,email',
			'username' 				=> 'required|unique:users,username',
			'password' 				=> 'required|confirmed|min:6',
			'photo' 				=> 'mimes:jpeg,gif,bmp,png',
		), $this->messages);

		if ($validation->fails()) {
			return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
		} else {
			$entry = new User;

			$entry->email 		= $request->email;
			$entry->username 	= $request->username;

			if ($request->has('password'))
				$entry->password 	= Hash::make((string) $request->password);

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

			// Send login data
	    	$data['email'] 		= $entry->email;
	    	$data['password'] 	= $request->password;

	    	Mail::send('emails.auth.created_by_admin', $data, function($message) use ($data) {
			    $message->from('info@apklausos.lt', 'Apklausos');
			    $message->subject('Prisijungimo duomenys');

			    $message->to($data['email']);
			});

			return redirect()
                ->route('users.index')
                ->withCreated($entry->id);
		}
	}

	public function edit($id)
	{
		$entry = User::find($id);

		if ($entry) {
			return view('backend.' . $this->view . '.edit')
				->withEntry($entry);
		}
		
		return redirect()->route('users.index');
	}

	public function update($id, Request $request)
	{
		$entry = User::find($id);

		if ($entry) {
			$validation = Validator::make($request->all(), array(
				'email' 				=> 'required|email|unique:users,email,' . $entry->id,
				'username' 				=> 'required|unique:users,username,' . $entry->id,
				'password' 				=> 'confirmed|min:6',
				'photo' 				=> 'mimes:jpeg,gif,bmp,png',
			), $this->messages);

			if ($validation->fails()) {
				return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validation->messages());
			} else {
				$entry->email 		= $request->email;
				$entry->username 	= $request->username;

				if ($request->has('password')) {
					$entry->password 	= Hash::make((string) $request->password);
					
					// Send login data
			    	$data['email'] 		= $entry->email;
			    	$data['password'] 	= $request->password;

			    	Mail::send('emails.auth.password_changed', $data, function($message) use ($data) {
					    $message->from('info@apklausos.lt', 'Apklausos');
					    $message->subject('Pakeistas slaptažodis');

					    $message->to($data['email']);
					});
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
                    ->route('users.index')
                    ->withUpdated($entry->id);
			}
		}
		
		return redirect()->route('users.index');
	}

	public function destroy($id)
	{
		$entry = User::find($id);

		if ($entry) {
			$entry->campaigns()->delete();
			$entry->delete();

			return redirect()
                ->route('users.index')
                ->withDeleted($entry->id);
		}
		
		return redirect()->route('users.index');
	}

}