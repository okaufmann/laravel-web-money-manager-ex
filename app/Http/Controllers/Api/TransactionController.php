<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Transformers\TransactionTransfomer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TransactionController extends Controller
{
    public function index()
    {
        $paginator = Transaction::paginate(10);
        $transactions = $paginator->getCollection();

        return fractal()
            ->collection($transactions)
            ->transformWith(new TransactionTransfomer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator));
    }
}
