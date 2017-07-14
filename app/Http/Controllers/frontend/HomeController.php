<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Campaign;

class HomeController extends Controller
{
    // View folder
    protected $view = 'home';

    function __construct()
    {
        // Access
//        $except = ['session_destroy'];

//        $this->beforeFilter('guest', ['except' => $except]);
    }

    public function index()
    {
        $entries = Campaign::where('advertise_results', '>', 0)
            ->where('active', '=', 1)
            ->orderBy('advertise_results', 'desc')
            ->where('public', '=', 1)
            ->take(5)
            ->get();

        $public_entries = Campaign::where('active', '=', 1)
            ->where('public', '=', 1)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('frontend.' . $this->view . '.index', [
            'entries' => $entries,
            'public_entries' => $public_entries
        ]);
    }
}
