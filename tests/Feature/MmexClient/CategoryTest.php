<?php

namespace Tests\Feature\MmexClient;

use App\Models\Category;
use App\Models\Payee;

class CategoryTest extends MmexTestCase
{
    /** @test */
    public function it_can_delete_all_categories()
    {
        // Arrange
        $categorie = factory(Category::class)->create(['user_id' => $this->user->id]);

        $url = $this->buildUrl(['delete_category' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertIsSoftDeletedInDatabase('categories', ['user_id' => $this->user->id, 'name' => $categorie->name]);
    }

    /** @test */
    public function it_can_import_categories()
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

    /** @test */
    public function it_can_import_sub_categories()
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

    /** @test */
    public function it_preserves_default_categories_on_payees_table_after_importing_categories()
    {
        // Arrange
        $category1 = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Bills']);
        $subcategory1 = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Services', 'parent_id' => $category1->id]);
        $category2 = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Food']);
        $subcategory2 = factory(Category::class)->create(['user_id' => $this->user->id, 'name' => 'Purchase', 'parent_id' => $category2->id]);

        $payee1 = factory(Payee::class)->create(['user_id' => $this->user->id, 'last_category_id' => $subcategory1->id]);
        $payee2 = factory(Payee::class)->create(['user_id' => $this->user->id, 'last_category_id' => $subcategory2->id]);

        $data = ['MMEX_Post' => '{ "Categories" : [ { "CategoryName" : "Bills", "SubCategoryName" : "Services" }, { "CategoryName" : "Food", "SubCategoryName" : "Purchase" } ] }'];

        // Act
        $deletePayeesUrl = $this->buildUrl(['delete_category' => 'true']);
        $this->getJson($deletePayeesUrl);

        $url = $this->buildUrl(['import_category' => 'true']);
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $category1 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['id' => $category1->id, 'name' => 'Bills', 'deleted_at' => null]);
        $subcategory1 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['id' => $subcategory1->id, 'name' => 'Services', 'parent_id' => $category1->id, 'deleted_at' => null]);

        $category2 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['id' => $category2->id, 'name' => 'Food', 'deleted_at' => null]);
        $subcategory2 = $this->assertDatabaseHasOnceAndReturnFirst('categories', ['id' => $subcategory2->id, 'name' => 'Purchase', 'parent_id' => $category2->id, 'deleted_at' => null]);

        $this->assertDatabaseHas('payees', ['id' => $payee1->id, 'last_category_id' => $subcategory1->id]);
        $this->assertDatabaseHas('payees', ['id' => $payee2->id, 'last_category_id' => $subcategory2->id]);
    }
}
