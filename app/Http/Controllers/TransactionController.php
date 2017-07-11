<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Auth;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TransactionRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        dd($request->all());
        /** @var Transaction $transaction */
        $transaction = Auth::user()->transactions()->create($request->all());

        $this->setResolvedFieldValues($request, $transaction);

        $transaction->save();

        if ($request->hasFile('attachments') && is_array($request->file('attachments'))) {
            foreach ($request->file('attachments') as $file) {
                $transaction->addAttachment($file);
            }
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->amount = $request->input('amount');
        $transaction->notes = $request->input('notes');
        $transaction->transaction_date = $request->input('transaction_date');

        $this->setResolvedFieldValues($request, $transaction);

        $transaction->save();

        if ($request->hasFile('attachments') && is_array($request->file('attachments'))) {
            foreach ($request->file('attachments') as $file) {
                $transaction->addAttachment($file);
            }
        }

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param TransactionRequest $request
     * @param $transaction
     */
    protected function setResolvedFieldValues(TransactionRequest $request, $transaction)
    {
        $type = TransactionType::findOrFail($request->input('transaction_type'));
        $transaction->type()->associate($type);

        $status = TransactionStatus::find($request->input('transaction_status'));
        if ($status) {
            $transaction->status()->associate($status);
        }

        $account = Account::find($request->input('account'));
        if ($account) {
            $transaction->account_name = $account->name;
        }

        $payee = Payee::find($request->input('payee'));
        if ($payee) {
            $transaction->payee_name = $payee->name;
        }

        $category = Category::rootCategories()->find($request->input('category'));
        if ($category) {
            $transaction->category_name = $category->name;

            $subcategory = Category::subCategories()->find($request->input('subcategory'));
            if ($subcategory) {
                $transaction->sub_category_name = $subcategory->name;
            }
        }
    }
}
