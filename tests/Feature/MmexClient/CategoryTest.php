<?php

namespace Tests\Feature\MmexClient;

use App\Models\Category;

class CategoryTest extends MmexTestCase
{
    public function testDeleteAllCategories()
    {
        // Arrange
        $categorie = factory(Category::class)->create(['user_id' => $this->user->id]);

        $url = $this->buildUrl(['delete_category' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseMissing('categories', ['user_id' => $this->user->id, 'name' => $categorie->name]);
    }

    public function testImportCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl(['import_category' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Bills']);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Telecom']);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Water']);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Automobile']);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Maintenance']);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Parking']);
    }

    public function testImportSubCategories()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Telecom" }, { "CategoryName" : "Bills", "SubCategoryName" : "Water" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Maintenance" }, { "CategoryName" : "Automobile", "SubCategoryName" : "Parking" } ] }'];
        $url = $this->buildUrl(['import_category' => 'true']);

        $bills = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Bills']);
        $automobile = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Automobile']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Bills', 'id' => $bills->id]);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Telecom', 'parent_id' => $bills->id]);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Water', 'parent_id' => $bills->id]);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Automobile', 'id' => $automobile->id]);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Maintenance', 'parent_id' => $automobile->id]);
        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Parking', 'parent_id' => $automobile->id]);
    }
}
