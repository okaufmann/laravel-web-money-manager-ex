<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 22.10.2016
 * Time: 14:53.
 */

namespace App\Services\Mmex;

use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use Log;

class ClientApiService
{
    public function getTransactions()
    {
        $transactions = Transaction::all();

        return $transactions;
    }

    public function deleteAccounts()
    {
        // TODO: find better method than where hack
        Account::where('id', '>', 0)->delete();
    }

    public function importBankAccounts($postData)
    {
        Log::debug('MmexController.importBankAccounts(), $accounts', [$postData->Accounts]);
        foreach ($postData->Accounts as $account) {
            Account::create([
                'name' => $account->AccountName,
            ]);
        }
    }

    public function deletePayees()
    {
        // TODO: find better method than where hack
        Payee::where('id', '>', 0)->delete();
    }

    public function importPayees($postData)
    {
        Log::debug('MmexController.importPayees(), $payees', [$postData->Payees]);

        foreach ($postData->Payees as $payee) {
            $categoryName = $payee->DefCateg;
            $subCategoryName = $payee->DefSubCateg;

            $category = Category::where('name', $subCategoryName)->whereHas('parentCategory', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })->first();

            if ($category) {
                $category->defaultForPayees()->create([
                    'name' => $payee->PayeeName,
                ]);
            } else {
                // ignore default/last category and just create entry
                Payee::create([
                    'name' => $payee->PayeeName,
                ]);
            }
        }
    }

    public function deleteCategories()
    {
        // TODO: find better method than where hack
        Category::where('id', '>', 0)->delete();
    }

    public function importCategories($postData)
    {
        $categories = collect($postData->Categories);

        $grouped = $categories->groupBy('CategoryName');

        foreach ($grouped as $categoryName => $subCategories) {
            $category = $this->createOrGetCategory($categoryName);

            foreach ($subCategories as $subCategory) {
                $this->createOrGetSubCategory($category, $subCategory->SubCategoryName);
            }
        }
    }

    private function createOrGetCategory($name)
    {
        $existingCategory = Category::whereName($name)->first();

        if ($existingCategory) {
            return $existingCategory;
        }

        $newCategory = Category::create([
            'name' => $name,
        ]);

        return $newCategory;
    }

    private function createOrGetSubCategory(Category $parentCategory, $name)
    {
        $existingCategory = Category::whereName($name)->first();

        if ($existingCategory) {
            return $existingCategory;
        }

        $newCategory = $parentCategory->subCategories()->create([
            'name' => $name,
        ]);

        return $newCategory;
    }

    public function deleteTransactions($transactionId)
    {
        Transaction::whereId($transactionId)->delete();
    }
}
