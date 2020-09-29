<?php

namespace App\Http\Controllers;

use LaravelLocalization;

use App\Models\Corpus\Text;
use App\Models\Dict\Dialect;
use App\Models\Dict\Lemma;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the start page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }   
}
