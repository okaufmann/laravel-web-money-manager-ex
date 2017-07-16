<?php

namespace App\Http\Controllers;

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
        $transaction = $this->transactionService->getTransaction($id);

        return view('home', compact('transaction'));
    }
}
