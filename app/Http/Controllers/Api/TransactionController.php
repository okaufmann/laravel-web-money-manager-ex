<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionRequest;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Services\TransactionService;
use App\Transformers\TransactionTransfomer;
use Auth;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * TransactionController constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {

        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $column = 'created_at';
        $direction = 'desc';

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

    public function store(TransactionRequest $request)
    {
        $data = collect($request->all());
        $user = Auth::user();

        // ensure payee exists
        $payeeName = $request->input('payee');
        $payee = Auth::user()->payees()->where($payeeName)->first();
        if (!empty($payeeName) && !$payee) {
            $payee = Auth::user()->payees()->create(['name' => $payeeName]);
        }

        $type = TransactionType::whereName($data->get('transaction_type'))->firstOrFail();
        $status = TransactionStatus::whereName($data->get('transaction_status'))->first();
        $account = $user->accounts()->whereName($data->get('account'))->firstOrFail();
        $toaccount = $user->accounts()->whereName($data->get('to_account'))->first();
        $category = $user->categories()->rootCategories()->whereName(($data->get('category')))->firstOrFail();
        $subcategory = $user->categories()->subCategories()->whereName($data->get('sub_category'))->first();

        $resolvedData = collect([
            'transaction_type'   => $type->id,
            'transaction_status' => $status ? $status->id : null,
            'account'            => $account->id,
            'to_account'         => $toaccount ? $toaccount->id : null,
            'payee'              => $payee->id,
            'category'           => $category->id,
            'subcategory'        => $subcategory ? $subcategory->id : null,
        ]);

        $data = $data->merge($resolvedData);

        $this->transactionService->createTransaction(Auth::user(), $data);

        return response("", 201);
    }
}
