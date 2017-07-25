<?php
/*
 * laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 */

namespace App\Services;

use App;
use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class TransactionService
{
    /**
     * Gets a transaction and set all related entities's ids if possible.
     *
     * @param $id
     *
     * @return Transaction
     */
    public function getTransaction(User $user, int $id)
    {
        $transaction = $user->transactions()->find($id);

        if (!$transaction) {
            return null;
        }

        $account = $user->accounts()->where('name', $transaction->account_name)->first();
        if ($account) {
            $transaction->account_id = $account->id;
        }
        $account = $user->accounts()->where('name', $transaction->to_account_name)->first();
        if ($account) {
            $transaction->to_account_id = $account->id;
        }

        $payee = $user->payees()->where('name', $transaction->payee_name)->first();
        if ($payee) {
            $transaction->payee_id = $payee->id;
        }

        $category = $user->categories()->where('name', $transaction->category_name)->first();
        if ($category) {
            $transaction->category_id = $category->id;
        }

        $category = $user->categories()->where('name', $transaction->sub_category_name)->first();

        if ($category) {
            $transaction->sub_category_id = $category->id;
        }

        return $transaction;
    }

    /**
     * Creates a new transaction and touch last used payee and payee's category.
     *
     * @param User       $user
     * @param Collection $data
     * @param array|null $files
     *
     * @return Transaction
     */
    public function createTransactionWithUsage(User $user, Collection $data, array $files = null)
    {
        $transaction = $this->createTransaction($user, $data, $files);

        $this->setPayeesLastUsedCategory($user, $data);
        $this->setPayeesLastUsedDate($user, $data);

        return $transaction;
    }

    /**
     * Creates a new transaction.
     *
     * @param User       $user
     * @param Collection $data
     * @param array|null $files
     *
     * @return Transaction
     */
    public function createTransaction(User $user, Collection $data, array $files = null, $jsonRequest = false)
    {
        $this->parseTransactionDate($data, $jsonRequest);

        $transaction = new Transaction($data->all());

        $this->setResolvedFieldValues($user, $data, $transaction);

        $user->transactions()->save($transaction);

        if ($files) {
            foreach ($files as $file) {
                $this->addAttachment($transaction, $file);
            }
        }

        return $transaction;
    }

    /**
     * Updates a existing transaction and touch last used payee and payee's category.
     *
     * @param User $user
     * @param $id
     * @param Collection $data
     * @param array|null $files
     *
     * @return mixed
     */
    public function updateTransactionWithUsage(User $user, $id, Collection $data, array $files = null)
    {
        $transaction = $this->updateTransaction($user, $id, $data, $files);

        $this->setPayeesLastUsedCategory($user, $data);
        $this->setPayeesLastUsedDate($user, $data);

        return $transaction;
    }

    public function updateTransaction(User $user, $id, Collection $data, array $files = null)
    {
        $this->parseTransactionDate($data);

        $transaction = $user->transactions()->findOrFail($id);

        $transaction->amount = $data->get('amount');
        $transaction->notes = $data->get('notes');
        $transaction->transaction_date = $data->get('transaction_date');

        $this->setResolvedFieldValues($user, $data, $transaction);

        $transaction->save();

        if ($files) {
            foreach ($files as $file) {
                $this->addAttachment($transaction, $file);
            }
        }

        return $transaction;
    }

    /**
     * @param $file string|UploadedFile
     * @param bool $keepOriginal
     */
    public function addAttachment(Transaction $transaction, $file, $keepOriginal = false)
    {
        if (is_string($file)) {
            $fileName = basename($file);
        } elseif ($file instanceof UploadedFile) {
            $fileName = $file->getFilename();
        } else {
            throw new \InvalidArgumentException('$file must be either a path or an UploadedFile!');
        }

        $fileName = 'Transaction_'.$transaction->id.'_'.$fileName;

        $media = $transaction->addMedia($file)
            ->usingFileName($fileName);

        if ($keepOriginal) {
            $media->preservingOriginal();
        }

        $media->toMediaCollection('attachments');
    }

    /**
     * @param Collection $data
     * @param $transaction
     *
     * @internal param TransactionRequest $request
     */
    private function setResolvedFieldValues(User $user, Collection $data, $transaction)
    {
        $type = TransactionType::findOrFail($data->get('transaction_type'));
        $transaction->type()->associate($type);

        $status = TransactionStatus::find($data->get('transaction_status'));
        if ($status) {
            $transaction->status()->associate($status);
        }

        $account = $user->accounts()->findOrFail($data->get('account'));
        $transaction->account_name = $account->name;

        $toaccount = $user->accounts()->find($data->get('to_account'));
        if ($toaccount) {
            $transaction->to_account_name = $toaccount->name;
        }

        $payee = $user->payees()->find($data->get('payee'));
        if ($payee) {
            $transaction->payee_name = $payee->name;
        }

        $category = $user->categories()->rootCategories()->findOrFail($data->get('category'));
        $transaction->category_name = $category->name;

        $subcategory = $user->categories()->subCategories()->find($data->get('subcategory'));
        if ($subcategory) {
            $transaction->sub_category_name = $subcategory->name;
        }
    }

    /**
     * @param Collection $data
     */
    private function setPayeesLastUsedCategory(User $user, Collection $data)
    {
        if ($data->get('subcategory')) {
            $lastCategory = $user->categories()->find($data->get('subcategory'));
        } else {
            $lastCategory = $user->categories()->find($data->get('category'));
        }

        if ($lastCategory) {
            $payee = $user->payees()->find($data->get('payee'));
            if ($payee) {
                $payee->lastCategoryUsed()->associate($lastCategory);
                $payee->save();
            }
        }
    }

    private function setPayeesLastUsedDate(User $user, Collection $data)
    {
        $payee = $user->payees()->find($data->get('payee'));

        if ($payee) {
            $payee->last_used_at = Carbon::now();
            $payee->save();
        }
    }

    private function parseTransactionDate($data, $jsonRequest = false)
    {
        $date = null;
        $transactionDate = $data->pull('transaction_date');

        if (!$transactionDate) {
            return;
        }

        $format = $jsonRequest ? Carbon::ATOM : locale_dateformat();

        $date = Carbon::createFromFormat($format, $transactionDate);
        $date->hour(0);
        $date->minute(0);
        $date->second(0);

        $data['transaction_date'] = $date;
    }
}
