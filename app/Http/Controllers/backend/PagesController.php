<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Auth;
use Validator;

class PagesController extends Controller
{
    // protected $fillable = ['title', 'slug', 'content', 'published'];
    // protected $table = 'pages';

    public function __construct()
    {
        //$this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Page $pageModel)
    {
        // return view('backend.pages.index');

        $pages = $pageModel->getPages();
        return view('backend.pages.index', ['pages' => $pages, 'title' => 'Pages']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.create', ['title' => 'Create page']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Page $pageModel, Request $request)
    {
        $validation = Validator::make($request->all(), array(
            'title'             => 'required',
            'slug'              => 'required',
        ));
        if ($validation->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation->messages());
        } else {
            $pageModel->create($request->all());
            return redirect()->route('pages.index');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('backend.pages.edit', [
            'page' => $page,
            'title' => 'Pages']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $page = Page::find($id);

      if ($page) {
        $page = Validator::make($request->all(), array(
          'title'             => 'required',
          'slug'              => 'required'
        ));

        if ($validation->fails()) {
          return redirect()
            ->back()
            ->withInput()
            ->withErrors($validation->messages());
        } else {
          $page->save();

          return redirect()
            ->route('pages.index')
            ->withUpdated($page->id);
        }
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
