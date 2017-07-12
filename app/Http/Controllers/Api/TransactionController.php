<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\TransactionTransfomer;
use Auth;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TransactionController extends Controller
{
    public function index()
    {
        $column = 'created_at';
        $direction = 'asc';

        // add support for sorting in vuetable-2
        $sort = request()->get('sort');
        if ($sort) {
            list($column, $direction) = explode('|', $sort);
        }

        $paginator = Auth::user()->transactions()
            ->orderBy($column, $direction)
            ->paginate(10);

        $transactions = $paginator->getCollection();

        return fractal()
            ->collection($transactions)
            ->transformWith(new TransactionTransfomer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator));
    }
}
