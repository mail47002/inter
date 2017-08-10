<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('backend.pages.index', [
            'pages' => Page::paginate()
        ]);
    }

    public function create()
    {
        return view('backend.pages.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required',
            'slug'          => 'required',
            'meta_title'    => 'required'
        ]);

        Page::create($request->all());

        return redirect()
            ->route('pages.index')
            ->withSuccess(trans('pages.create'));
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
            $this->validate($request, [
                'title'         => 'required',
                'slug'          => 'required',
                'meta_title'    => 'required'
            ]);

            $page->fill($request->all())->save();

            return redirect()
                ->route('pages.index')
                ->withSuccess(trans('pages.update'));
        }
    }

    public function destroy($id)
    {
        $page = Page::find($id);

        if ($page) {
            $page->delete();

            return redirect()
                ->route('pages.index')
                ->withSuccess(trans('pages.delete'));
        }

        return redirect()->route('pages.index');
    }
}
