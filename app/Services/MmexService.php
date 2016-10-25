<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 22.10.2016
 * Time: 14:53
 */

namespace App\Services;


use App\Models\Account;
use App\Models\Transaction;
use Log;

class MmexService
{
    public function getTransactions() {
        return "{    \"0\": {        \"ID\": \"1\",        \"Date\": \"2016-10-07\",        \"Account\": \"Keine Auswahl\",        \"ToAccount\": \"None\",        \"Status\": \"R\",        \"Type\": \"Zahlung\",        \"Payee\": \"Migros\",        \"Category\": \"Einkauf\",        \"SubCategory\": \"Etwas anderes\",        \"Amount\": \"123\",        \"Notes\": \"Das ist ein \r\nMeeeeehrzeiliger \r\nT\",        \"Attachments\": \"Transaction_1_Attach1.png;Transaction_1_Attach2.jpg\"    },    \"1\": {        \"ID\": \"2\",        \"Date\": \"2016-10-18\",        \"Account\": \"Keine Auswahl\",        \"ToAccount\": \"None\",        \"Status\": \"R\",        \"Type\": \"Zahlung\",        \"Payee\": \"Migros\",        \"Category\": \"Test\",        \"SubCategory\": \"test\",        \"Amount\": \"2\",        \"Notes\": \"\",        \"Attachments\": \"\"    }}";
    }

    public function deleteAccounts()
    {
        Account::where('id','>',0)->delete();
    }

    public function importBankAccounts($postData)
    {
        Log::debug('MmexController, $accounts', [$postData->Accounts]);
        foreach ($postData->Accounts as $account){
            Account::create([
                'name' => $account->AccountName
            ]);
        }
    }
}