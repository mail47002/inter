<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Auth;
use Validator;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['admin', 'auth']);
    }

    public function index()
    {
        return view('backend.pages.index', [
            'pages' => Page::all()
        ]);
    }

    public function create()
    {
        return view('backend.pages.create');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), array(
            'title'     => 'required',
            'slug'      => 'required',
        ));

        if ($validation->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
        } else {
            Page::create($request->all());

            return redirect()->route('pages.index');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $page = Page::find($id);

        if ($page) {
            return view('backend.pages.edit', [
                'page' => $page
            ]);
        }

        return redirect()->route('pages.index');
    }


    public function update($id, Request $request)
    {
        $page = Page::find($id);

        if ($page) {
            $validation = Validator::make($request->all(), array(
                'title'     => 'required',
                'slug'      => 'required'
            ));

            if ($validation->fails()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validation->messages());
            } else {
                $page->fill($request->all())->save();

                return redirect()
                    ->route('pages.index')
                    ->withUpdated($page->id);
            }
        }
    }

    public function destroy($id)
    {
        $page = Page::find($id);

        if ($page) {
            $page->campaigns()->delete();
            $page->delete();

            return redirect()
                ->route('pages.index')
                ->withDeleted($page->id);
        }

        return redirect()->route('pages.index');
    }
}
