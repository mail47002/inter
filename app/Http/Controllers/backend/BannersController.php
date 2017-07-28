<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Banner;

class BannersController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('backend.banners.index', [
            'banners' => Banner::paginate()
        ]);
    }

    public function create()
    {
        return view('backend.banners.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required',
            'position'  => 'required'
        ]);

        Banner::create($request->all());

        return redirect()
            ->route('banners.index')
            ->withStatus(trans('banners.create'));
    }


    public function edit($id)
    {
        $banner = Banner::find($id);

        if ($banner) {
            return view('backend.banners.edit', [
                'banner' => $banner
            ]);
        }

        return redirect()->route('banners.index');
    }

    public function update($id, Request $request)
    {
        $banner = Banner::find($id);

        if ($banner) {
            $this->validate($request, [
                'name'      => 'required',
                'position'  => 'required'
            ]);

            $banner->fill($request->all())->save();

            return redirect()
                ->route('banners.index')
                ->withStatus(trans('banners.update'));
        }
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);

        if ($banner) {
            $banner->delete();

            return redirect()
                ->route('banners.index')
                ->withStatus(trans('banners.delete'));
        }

        return redirect()->route('banners.index');
    }
}
