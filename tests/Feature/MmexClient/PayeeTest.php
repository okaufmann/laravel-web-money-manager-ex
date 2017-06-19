<?php

namespace Tests\Feature\MmexClient;

use App\Models\Category;
use App\Models\Payee;

class PayeeTest extends MmexTestCase
{
    public function testDeleteAllPayees()
    {
        // Arrange
        $payee = factory(Payee::class)->create();
        $url = $this->buildUrl('', ['delete_payee' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseMissing('payees', ['name' => $payee->name]);
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

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseHas('payees', ['name' => 'Mc Donalds', 'last_category_id' => $foodPurchases->id]);
        $this->assertDatabaseHas('payees', ['name' => 'Spotify', 'last_category_id' => $billsServices->id]);
    }
}
