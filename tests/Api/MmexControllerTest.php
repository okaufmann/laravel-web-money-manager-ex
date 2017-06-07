<?php
/**
 * MmexControllerTest.php, laravel-money-manager-ex.
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 *
 * @version 1.0
 */

namespace App\Tests\Api;

use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;

class MmexControllerTest extends AbstractApiTestCase
{
    public function testDeleteAllPayees()
    {
        // Arrange
        $payee = factory(Payee::class)->create();
        $url = $this->buildUrl('', ['delete_payee' => 'true']);

        // Act & Assert
        $this->get($url)
            ->seeSuccess()
            ->dontSeeInDatabase('payees', ['name' => $payee->name]);
    }

    public function testImportPayees()
    {
        // Arrange
        $food = factory(Category::class)->create(['name' => 'Food']);
        $foodPurchases = factory(Category::class)->create(['name' => 'Purchases', 'parent_id' => $food->id]);
        $bills = factory(Category::class)->create(['name' => 'Bills']);
        $billsServices = factory(Category::class)->create(['name' => 'Services', 'parent_id' => $bills->id]);

        $data = ['MMEX_Post' => '{ "Payees" : [ { "PayeeName" : "Mc Donalds", "DefCateg" : "'.$food->name.'", "DefSubCateg" : "'.$foodPurchases->name.'" },'.
            '{ "PayeeName" : "Spotify", "DefCateg" : "'.$bills->name.'", "DefSubCateg" : "'.$billsServices->name.'" } ] }',];

        $url = $this->buildUrl('', ['import_payee' => 'true']);

        // Act & Assert
        $this->postJson($url, $data)
            ->seeSuccess()
            ->seeInDatabase('payees', ['name' => 'Mc Donalds', 'last_category_id' => $foodPurchases->id])
            ->seeInDatabase('payees', ['name' => 'Spotify', 'last_category_id' => $billsServices->id]);
    }

    public function testDeleteAllAccounts()
    {
        // Arrange
        $account = factory(Account::class)->create();
        $url = $this->buildUrl('', ['delete_bankaccount' => 'true']);

        // Act & Assert
        $this->get($url)
            ->seeSuccess()
            ->dontSeeInDatabase('accounts', ['name' => $account->name]);
    }

    public function testImportAccounts()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Accounts" : [ { "AccountName" : "Creditcard" }, { "AccountName" : "Private Account" } ] }'];
        $url = $this->buildUrl('', ['import_bankaccount' => 'true']);

        // Act & Assert
        $this->postJson($url, $data)
            ->seeSuccess()
            ->seeInDatabase('accounts', ['name' => 'Creditcard'])
            ->seeInDatabase('accounts', ['name' => 'Private Account']);
    }

    public function testDeleteAllCategories()
    {
        // Arrange
        $categorie = factory(Category::class)->create();

        $url = $this->buildUrl('', ['delete_category' => 'true']);

        // Act & Assert
        $this->get($url)
            ->seeSuccess()
            ->dontSeeInDatabase('categories', ['name' => $categorie->name]);
    }

    public function testImportCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl('', ['import_category' => 'true']);

        // Act & Assert
        $this->postJson($url, $data)
            ->seeSuccess()
            ->seeInDatabase('categories', ['name' => 'Bills'])
            ->seeInDatabase('categories', ['name' => 'Telecom'])
            ->seeInDatabase('categories', ['name' => 'Water'])
            ->seeInDatabase('categories', ['name' => 'Automobile'])
            ->seeInDatabase('categories', ['name' => 'Maintenance'])
            ->seeInDatabase('categories', ['name' => 'Parking']);
    }

    public function testImportSubCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl('', ['import_category' => 'true']);

        $bills = factory(Category::class)->create(['name' => 'Bills']);
        $automobile = factory(Category::class)->create(['name' => 'Automobile']);

        // Act & Assert
        $this->postJson($url, $data)
            ->seeSuccess()
            ->seeInDatabase('categories', ['name' => 'Bills', 'id' => $bills->id])
            ->seeInDatabase('categories', ['name' => 'Telecom', 'parent_id' => $bills->id])
            ->seeInDatabase('categories', ['name' => 'Water', 'parent_id' => $bills->id])
            ->seeInDatabase('categories', ['name' => 'Automobile', 'id' => $automobile->id])
            ->seeInDatabase('categories', ['name' => 'Maintenance', 'parent_id' => $automobile->id])
            ->seeInDatabase('categories', ['name' => 'Parking', 'parent_id' => $automobile->id]);
    }

    public function testDeleteTransactions()
    {
        // Arrange
        $transaction = factory(Transaction::class)->create();
        $url = $this->buildUrl('', ['delete_group' => $transaction->id]);

        // Act & Assert
        $this->get($url)
            ->seeSuccess()
            ->seeIsSoftDeletedInDatabase('transactions', ['payee_name' => $transaction->payee_name]); // must not be deleted! just soft deleted.
    }

    public function testDownloadTransactions()
    {
        // Arrange
        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create();
        $this->addReceiptsToTransaction($transaction);
        $url = $this->buildUrl('', ['download_transaction' => 'true']);

        // Act & Assert
        $this->get($url)
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'ID'          => $transaction->id,
                    'Date'        => $transaction->date,
                    'Account'     => $transaction->account_name,
                    'ToAccount'   => $transaction->to_account_name,
                    'Status'      => $transaction->status->slug,
                    'Type'        => $transaction->type->name,
                    'Payee'       => $transaction->payee_name,
                    'Category'    => $transaction->category_name,
                    'SubCategory' => $transaction->sub_category_name,
                    'Amount'      => $transaction->amount,
                    'Notes'       => $transaction->notes,
                    'Attachments' => 'Transaction_'.$transaction->id.'_test-receipt.png;Transaction_'.$transaction->id
                        .'_test-receipt-2.png;Transaction_'.$transaction->id.'_test-receipt-3.png'
                ]
            );
    }

    /**
     * Attachment file name will be provided as comma separated list in the transaction download.
     */
    public function testDownloadAttachment()
    {
        // Arrange
        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create();
        $this->addReceiptsToTransaction($transaction);
        $fileName = 'Transaction_'.$transaction->id.'_test-receipt-3.png';
        $url = $this->buildUrl('', ['download_attachment' => $fileName]);

        // Act & Assert
        $this->get($url)
            ->seeStatusCode(200)
            ->seeHeader('Content-Type', '')
            ->seeHeader('Cache-Control', 'public')
            ->seeHeader('Content-Description', 'File Transfer')
            ->seeHeader('Content-Disposition', 'attachment; filename= '.$fileName)
            ->seeHeader('Content-Transfer-Encoding', 'binary');
    }

    /**
     * @param $transaction
     */
    protected function addReceiptsToTransaction(Transaction $transaction)
    {
        $transaction->addAttachment(base_path('tests/data/test-receipt.png'), true);
        $transaction->addAttachment(base_path('tests/data/test-receipt-2.png'), true);
        $transaction->addAttachment(base_path('tests/data/test-receipt-3.png'), true);
    }
}
