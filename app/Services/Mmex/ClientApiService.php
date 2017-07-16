<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 22.10.2016
 * Time: 14:53.
 */

namespace App\Services\Mmex;

use App\Models\Category;
use App\Models\User;
use Log;
use Spatie\MediaLibrary\Media;

class ClientApiService
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTransactions(User $user)
    {
        $transactions = $user->transactions;

        return $transactions;
    }

    public function deleteAccounts(User $user)
    {
        // TODO: find better method than where hack
        $user->accounts()->where('id', '>', 0)->delete();
    }

    public function importBankAccounts(User $user, $postData)
    {
        Log::debug('MmexController.importBankAccounts(), $accounts', [$postData->Accounts]);
        foreach ($postData->Accounts as $account) {
            $user->accounts()->create([
                'name' => $account->AccountName,
            ]);
        }
    }

    public function deletePayees(User $user)
    {
        // TODO: find better method than where hack
        $user->payees()->where('id', '>', 0)->delete();
    }

    public function importPayees(User $user, $postData)
    {
        Log::debug('MmexController.importPayees(), $payees', [$postData->Payees]);

        foreach ($postData->Payees as $payeeData) {
            $categoryName = $payeeData->DefCateg;
            $subCategoryName = $payeeData->DefSubCateg;

            $category = $user->categories()->where('name', $subCategoryName)->whereHas('parentCategory', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })->first();

            $existingPayee = $user->payees()->onlyTrashed()->where('name', $payeeData->PayeeName)->first();

            if ($existingPayee) {
                $existingPayee->restore();
                $payee = $existingPayee;
            } else {
                $payee = $user->payees()->create([
                    'name' => $payeeData->PayeeName,
                ]);
            }

            if ($category) {
                $category->defaultForPayees()->save($payee);
            }
        }

        $user->payees()->onlyTrashed()->forceDelete();
    }

    public function deleteCategories(User $user)
    {
        // TODO: find better method than where hack
        $user->categories()->where('id', '>', 0)->delete();
    }

    public function importCategories(User $user, $postData)
    {
        $categories = collect($postData->Categories);

        $grouped = $categories->groupBy('CategoryName');

        foreach ($grouped as $categoryName => $subCategories) {
            $category = $this->createOrGetCategory($user, $categoryName);

            foreach ($subCategories as $subCategory) {
                $this->createOrGetSubCategory($user, $category, $subCategory->SubCategoryName);
            }
        }
    }

    public function deleteTransactions(User $user, $transactionId)
    {
        $user->transactions()->whereId($transactionId)->delete();
    }

    /**
     * @param User $user
     * @param $fileName
     *
     * @return Media
     */
    public function getAttachment(User $user, $fileName)
    {
        // extract transaction
        $fileNameParts = explode('_', $fileName);
        $transactionId = $fileNameParts[1];
        $transaction = $user->transactions()->findOrFail($transactionId);

        // get attachment of transaction
        $media = $transaction->getMedia('attachments')->first(function ($item) use ($fileName) {
            return $item->file_name == $fileName;
        });

        return $media;
    }

    /**
     * @param User $user
     * @param $name
     *
     * @return Category
     */
    private function createOrGetCategory(User $user, $name)
    {
        $existingCategory = $user->categories()->whereName($name)->first();

        if ($existingCategory) {
            return $existingCategory;
        }

        $newCategory = $user->categories()->create([
            'name' => $name,
        ]);

        return $newCategory;
    }

    private function createOrGetSubCategory(User $user, Category $parentCategory, $name)
    {
        $existingCategory = $user->categories()->whereName($name)->first();

        if ($existingCategory) {
            return $existingCategory;
        }

        $newCategory = $user->categories()->create([
            'name' => $name,
        ]);

        $parentCategory->categories()->save($newCategory);

        return $newCategory;
    }
}
