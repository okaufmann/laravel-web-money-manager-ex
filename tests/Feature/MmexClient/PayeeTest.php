<?php

namespace Tests\Feature\MmexClient;

use App\Models\Category;
use App\Models\Payee;

class PayeeTest extends MmexTestCase
{
    /** @test */
    public function it_can_delete_all_payees()
    {
        // Arrange
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $url = $this->buildUrl(['delete_payee' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertIsSoftDeletedInDatabase('payees', ['user_id' => $this->user->id, 'name' => $payee->name]);
    }

    /** @test */
    public function it_can_import_payees()
    {
        // Arrange
        $food = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Food']);
        $foodPurchases = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Purchases', 'parent_id' => $food->id]);
        $bills = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Bills']);
        $billsServices = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Services', 'parent_id' => $bills->id]);

        $data = ['MMEX_Post' => '{ "Payees" : [ { "PayeeName" : "Mc Donalds", "DefCateg" : "'.$food->name.'", "DefSubCateg" : "'.$foodPurchases->name.'" },'.
            '{ "PayeeName" : "Spotify", "DefCateg" : "'.$bills->name.'", "DefSubCateg" : "'.$billsServices->name.'" } ] }', ];

        $url = $this->buildUrl(['import_payee' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'name' => 'Mc Donalds', 'last_category_id' => $foodPurchases->id]);
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'name' => 'Spotify', 'last_category_id' => $billsServices->id]);
    }

    /** @test */
    public function it_can_ensure_that_soft_deleted_payees_will_be_restored()
    {
        // Arrange
        $payee1 = factory(Payee::class)->create(['user_id' => $this->user->id, 'name' => 'Luke Skywalker']);
        $payee2 = factory(Payee::class)->create(['user_id' => $this->user->id, 'name' => 'Yoda']);

        // will soft delete entries
        $payee1->delete();
        $payee2->delete();

        $data = ['MMEX_Post' => '{ "Payees" : [ { "PayeeName" : "Luke Skywalker", "DefCateg" : "None", "DefSubCateg" : "None" },'.
            '{ "PayeeName" : "Yoda", "DefCateg" : "None", "DefSubCateg" : "None" } ] }', ];

        $url = $this->buildUrl(['import_payee' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'name' => 'Luke Skywalker', 'deleted_at' => null]);
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'name' => 'Yoda', 'deleted_at' => null]);
    }

    /** @test */
    public function it_creates_default_categories_for_payees()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Payees" : [ { "PayeeName" : "Mc Donalds", "DefCateg" : "Food", "DefSubCateg" : "Purchase" },'.
            '{ "PayeeName" : "Spotify", "DefCateg" : "Bills", "DefSubCateg" : "Services" } ] }', ];

        $url = $this->buildUrl(['import_payee' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $category1 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['user_id' => $this->user->id, 'name' => 'Food']);
        $category2 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['user_id' => $this->user->id, 'name' => 'Bills']);

        $subcategory1 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['user_id' => $this->user->id, 'name' => 'Purchase', 'parent_id' => $category1->id]);
        $subcategory2 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['user_id' => $this->user->id, 'name' => 'Services', 'parent_id' => $category2->id]);

        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'name' => 'Mc Donalds', 'last_category_id' => $subcategory1->id]);
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'name' => 'Spotify', 'last_category_id' => $subcategory2->id]);
    }
}
