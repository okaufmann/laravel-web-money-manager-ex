<?php

namespace Tests\Feature\MmexClient;

use App\Models\Category;
use Tests\Feature\Api\AbstractMmexTestCase;

class CategoryTest extends AbstractMmexTestCase
{
    public function testDeleteAllCategories()
    {
        // Arrange
        $categorie = factory(Category::class)->create();

        $url = $this->buildUrl('', ['delete_category' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseMissing('categories', ['name' => $categorie->name]);
    }

    public function testImportCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl('', ['import_category' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseHas('categories', ['name' => 'Bills']);
        $this->assertDatabaseHas('categories', ['name' => 'Telecom']);
        $this->assertDatabaseHas('categories', ['name' => 'Water']);
        $this->assertDatabaseHas('categories', ['name' => 'Automobile']);
        $this->assertDatabaseHas('categories', ['name' => 'Maintenance']);
        $this->assertDatabaseHas('categories', ['name' => 'Parking']);
    }

    public function testImportSubCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl('', ['import_category' => 'true']);

        $bills = factory(Category::class)->create(['name' => 'Bills']);
        $automobile = factory(Category::class)->create(['name' => 'Automobile']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseHas('categories', ['name' => 'Bills', 'id' => $bills->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Telecom', 'parent_id' => $bills->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Water', 'parent_id' => $bills->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Automobile', 'id' => $automobile->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Maintenance', 'parent_id' => $automobile->id]);
        $this->assertDatabaseHas('categories', ['name' => 'Parking', 'parent_id' => $automobile->id]);
    }
}
