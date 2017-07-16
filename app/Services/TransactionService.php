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
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getTransaction(int $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return null;
        }

        $account = Account::where('name', $this->account_name)->first();
        if ($account) {
            $transaction->account_id = $account->id;
        }
        $account = Account::where('name', $this->to_account_name)->first();
        if ($account) {
            $transaction->to_account_id = $account->id;
        }

        $payee = Payee::where('name', $this->payee_name)->first();
        if ($payee) {
            $transaction->payee_id = $payee->id;
        }

        $category = Category::where('name', $this->category_name)->first();
        if ($category) {
            $transaction->category_id = $category->id;
        }

        $category = Category::where('name', $this->sub_category_name)->first();

        if ($category) {
            $transaction->sub_category_id = $category->id;
        }

        return $transaction;
    }

    public function createTransaction(User $user, Collection $data, array $files = null)
    {
        $transaction = new Transaction($data->all());

        $this->setResolvedFieldValues($data, $transaction);

        $user->transactions()->save($transaction);

        $this->setPayeesLastUsedCategory($data);
        $this->setPayessLastUsedDate($data);

        if ($files) {
            foreach ($files as $file) {
                $this->addAttachment($transaction, $file);
            }
        }

    }

    public function updateTransaction(User $user, $id, Collection $data, array $files = null)
    {
        $transaction = $user->transactions()->findOrFail($id);

        $transaction->amount = $data->get('amount');
        $transaction->notes = $data->get('notes');
        $transaction->transaction_date = $data->get('transaction_date');

        $this->setResolvedFieldValues($data, $transaction);

        $transaction->save();

        $this->setPayeesLastUsedCategory($data);
        $this->setPayessLastUsedDate($data);

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
     * @internal param TransactionRequest $request
     */
    private function setResolvedFieldValues(Collection $data, $transaction)
    {
        $type = TransactionType::findOrFail($data->get('transaction_type'));
        $transaction->type()->associate($type);

        $status = TransactionStatus::find($data->get('transaction_status'));
        if ($status) {
            $transaction->status()->associate($status);
        }

        $account = Account::find($data->get('account'));
        if ($account) {
            $transaction->account_name = $account->name;
        }

        $toaccount = Account::find($data->get('to_account'));
        if ($toaccount) {
            $transaction->to_account_name = $toaccount->name;
        }

        $payee = Payee::find($data->get('payee'));
        if ($payee) {
            $transaction->payee_name = $payee->name;
        }

        $category = Category::rootCategories()->find($data->get('category'));
        if ($category) {
            $transaction->category_name = $category->name;

            $subcategory = Category::subCategories()->find($data->get('subcategory'));
            if ($subcategory) {
                $transaction->sub_category_name = $subcategory->name;
            }
        }
    }

    /**
     * @param Collection $data
     */
    private function setPayeesLastUsedCategory(Collection $data)
    {
        if ($data->get('subcategory')) {
            $lastCategory = Category::find($data->get('subcategory'));
        } else {
            $lastCategory = Category::find($data->get('category'));
        }

        if ($lastCategory) {
            $payee = Payee::find($data->get('payee'));
            $payee->lastCategoryUsed()->associate($lastCategory);
            $payee->save();
        }
    }

    private function setPayessLastUsedDate(Collection $data)
    {
        $payee = Payee::find($data->get('payee'));

        if ($payee) {
            $payee->last_used_at = Carbon::now();
            $payee->save();
        }
    }
}
