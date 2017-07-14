<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        $transaction = Transaction::find($id);

        return view('home', compact('transaction'));
    }
}
