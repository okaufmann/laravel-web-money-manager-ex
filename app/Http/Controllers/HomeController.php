<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Transaction;
use App\Services\TransactionService;

class HomeController extends Controller
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * Create a new controller instance.
     *
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->middleware('auth');
        $this->transactionService = $transactionService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        $transaction = null;
        if ($id) {
            $transaction = $this->transactionService->getTransaction(Auth::user(), $id);
        }

        return view('home', compact('transaction'));
    }
}
