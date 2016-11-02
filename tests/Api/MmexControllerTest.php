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

class MmexControllerTest extends AbstractApiTestCase
{
    public function testDeleteAllPayees()
    {
        // Arrange
        $payee = factory(Payee::class)->create();

        $url = $url = $this->buildUrl('', ['delete_payee' => 'true']);

        //Assert
        $this->get($url)
            ->see($this->success)
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
            '{ "PayeeName" : "Spotify", "DefCateg" : "'.$bills->name.'", "DefSubCateg" : "'.$billsServices->name.'" } ] }', ];

        $url = $this->buildUrl('', ['import_payee' => 'true']);

        // Assert
        $this->postJson($url, $data)
            ->see($this->success)
            ->seeInDatabase('payees', ['name' => 'Mc Donalds', 'last_category_id' => $foodPurchases->id])
            ->seeInDatabase('payees', ['name' => 'Spotify', 'last_category_id' => $billsServices->id]);
    }

    public function testDeleteAllAccoutns()
    {
        // Arrange
        $account = factory(Account::class)->create();

        $url = $url = $this->buildUrl('', ['delete_bankaccount' => 'true']);

        //Assert
        $this->get($url)
            ->see($this->success)
            ->dontSeeInDatabase('accounts', ['name' => $account->name]);
    }

    public function testImportAccounts()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Accounts" : [ { "AccountName" : "Creditcard" }, { "AccountName" : "Private Account" } ] }'];
        $url = $this->buildUrl('', ['import_bankaccount' => 'true']);

        // Assert
        $this->postJson($url, $data)
            ->see($this->success)
            ->seeInDatabase('accounts', ['name' => 'Creditcard'])
            ->seeInDatabase('accounts', ['name' => 'Private Account']);
    }

    public function testDeleteAllCategories()
    {
        // Arrange
        $categorie = factory(Category::class)->create();

        $url = $url = $this->buildUrl('', ['delete_category' => 'true']);

        //Assert
        $this->get($url)
            ->see($this->success)
            ->dontSeeInDatabase('categories', ['name' => $categorie->name]);
    }

    public function testImportCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl('', ['import_category' => 'true']);

        // Assert
        $this->postJson($url, $data)
            ->see($this->success)
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

        // Assert
        $this->postJson($url, $data)
            ->see($this->success)
            ->seeInDatabase('categories', ['name' => 'Bills', 'id' => $bills->id])
            ->seeInDatabase('categories', ['name' => 'Telecom', 'parent_id' => $bills->id])
            ->seeInDatabase('categories', ['name' => 'Water', 'parent_id' => $bills->id])
            ->seeInDatabase('categories', ['name' => 'Automobile', 'id' => $automobile->id])
            ->seeInDatabase('categories', ['name' => 'Maintenance', 'parent_id' => $automobile->id])
            ->seeInDatabase('categories', ['name' => 'Parking', 'parent_id' => $automobile->id]);
    }
}
