<?php

namespace App\Http\Controllers\backend;
//hello
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function edit()
    {
        return view('backend.account.edit', [
            'user' => Auth::guard('admin')->user()
        ]);
    }

    public function update(Request $request)
    {
        $entry = Auth::guard('admin')->user();

        $this->validate($request, [
            'email' 	=> 'required|email|unique:users,email,' . $entry->id,
            'username'  => 'required|unique:users,username,' . $entry->id,
            'password' 	=> 'nullable|min:6',
            'photo' 	=> 'mimes:jpeg,gif,bmp,png',
        ]);

        $entry->email 		= $request->email;
        $entry->username 	= $request->username;
        $entry->status  	= 1;

        if (!empty($request->password)) {
            $entry->password = Hash::make((string) $request->password);
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
            ->back()
            ->withStatus(trans('account.update'));
    }
}
