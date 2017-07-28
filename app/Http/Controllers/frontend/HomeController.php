<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Campaign;

class HomeController extends Controller
{
    public function index()
    {
        $entries = Campaign::where('advertise_results', '>', 0)
            ->where('active', '=', 1)
            ->orderBy('advertise_results', 'desc')
            ->where('public', '=', 1)
            ->take(5)
            ->get();

        $public_entries = Campaign::recentPublic()->take(5)->get();

        return view('frontend.home.index', [
            'entries'           => $entries,
            'public_entries'    => $public_entries
        ]);
    }
}
