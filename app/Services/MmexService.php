<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 22.10.2016
 * Time: 14:53
 */

namespace App\Services;


use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use Log;

class MmexService
{
    public function getTransactions()
    {
        return "{    \"0\": {        \"ID\": \"1\",        \"Date\": \"2016-10-07\",        \"Account\": \"Keine Auswahl\",        \"ToAccount\": \"None\",        \"Status\": \"R\",        \"Type\": \"Zahlung\",        \"Payee\": \"Migros\",        \"Category\": \"Einkauf\",        \"SubCategory\": \"Etwas anderes\",        \"Amount\": \"123\",        \"Notes\": \"Das ist ein \r\nMeeeeehrzeiliger \r\nT\",        \"Attachments\": \"Transaction_1_Attach1.png;Transaction_1_Attach2.jpg\"    },    \"1\": {        \"ID\": \"2\",        \"Date\": \"2016-10-18\",        \"Account\": \"Keine Auswahl\",        \"ToAccount\": \"None\",        \"Status\": \"R\",        \"Type\": \"Zahlung\",        \"Payee\": \"Migros\",        \"Category\": \"Test\",        \"SubCategory\": \"test\",        \"Amount\": \"2\",        \"Notes\": \"\",        \"Attachments\": \"\"    }}";
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
                'name' => $account->AccountName
            ]);
        }
    }

    public function deletePayees()
    {
        // TODO: find better method than where hack
        Payee::where('id', '>', 0)->delete();
    }

    public function importPayees($postData){
        Log::debug('MmexController.importPayees(), $payees', [$postData->Payees]);
        foreach ($postData->Payees as $payee) {
            Payee::create([
                'name' => $payee->PayeeName
            ]);
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

        $grouped = $categories->groupBy("CategoryName");

        dd($grouped);

    }


}