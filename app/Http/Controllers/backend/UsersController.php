<?php

namespace App\Http\Controllers\backend;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;
use File;
use Image;
use App\Http\Controllers\Controller;
use Hash;
use Mail;


class UsersController extends Controller
{

    protected $messages = [
        'required'		=> 'Required',
        'email'			=> 'Invalid email mail adress',
        'enough'		=> 'Not enough credits',
        'min'			=> 'The value must contain at least: min characters',
        'mimes'			=> 'Invalid format. Available formats: <em> jpeg, gif, bmp, png </ em>.',
        'confirmed'		=> 'Passwords do not match',
        'unique'		=> 'Already existent',
    ];

	public function __construct()
	{
	    // $this->middleware('admin');
	}

	public function index(User $userModel)
	{
		$users = $userModel->getUsers();
		return view('backend.users.index', ['users' => $users, 'title' => 'User']);
	}

	public function create()
	{
		return view('backend.users.create', ['title' => 'New User']);
	}

	public function store(Request $request)
	{
		$validation = Validator::make($request->all(), array(
			'email' 				=> 'required|email|unique:users,email',
			'username' 				=> 'required|unique:users,username',
			'password' 				=> 'required|confirmed|min:6',
			'photo' 				=> 'mimes:jpeg,gif,bmp,png',
		));

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
			$entry->status 	= $request->status;

			$entry->save();

			// Send login data
	    	$data['email'] 		= $entry->email;
	    	$data['password'] 	= $request->password;

	   //  	Mail::send('emails.auth.created_by_admin', $data, function($message) use ($data) {
			 //    $message->from('info@apklausos.lt', 'Apklausos');
			 //    $message->subject('Prisijungimo duomenys');

			 //    $message->to($data['email']);
				// });

			return redirect()
                ->route('users.index')
                ->withCreated($entry->id);
		}
	}

	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

	public function edit(User $user)
	{

		if ($user) {
			 return view('backend.users.edit', [
            'user' => $user,
            'title' => 'Edit User']);
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
				'password' 				=> 'nullable|min:6',
				'photo' 				=> 'mimes:jpeg,gif,bmp,png',
			));

			if ($validation->fails()) {
				return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validation->messages());
			} else {
				$entry->email 		= $request->email;
				$entry->username 	= $request->username;

				if (!empty($request->password)) {
					$entry->password 	= Hash::make((string) $request->password);
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

				$entry->status 	= $request->has('status') ? 1 : 0;

				$entry->save();

				// Send login data
	    	$data['email'] 		= $entry->email;
	    	$data['password'] 	= $request->password;

	    	// Mail::send('emails.auth.password_changed', $data, function($message) use ($data) {
			   //  $message->from('info@apklausos.lt', 'Apklausos');
			   //  $message->subject('Pakeistas slaptažodis');

			   //  $message->to($data['email']);
			   //  });

				return redirect()
                    ->route('users.index')
                     ->withUpdated($entry->id);
			}
		}

		return redirect()->route('users.index');
	}

	public function destroy($id, Request $request)
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