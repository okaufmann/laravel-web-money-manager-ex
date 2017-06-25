<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function it_return_all_subcategories_of_a_category()
    {

        // Arrange
        $category = factory(Category::class)->create();
        $subCategory1 = factory(Category::class)->create(['parent_id' => $category->id]);
        $subCategory2 = factory(Category::class)->create(['parent_id' => $category->id]);

        $url = '/api/v1/category/%d/subcategories';
        $url = sprintf($url, $category->id);

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id'   => $subCategory1->id,
                'name' => $subCategory1->name,
            ])->assertJsonFragment([
                'id'   => $subCategory2->id,
                'name' => $subCategory2->name,
            ]);
    }

    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function it_can_access_all_root_categories()
    {

        // Arrange
        $category1 = factory(Category::class)->create();
        $category2 = factory(Category::class)->create();
        $subCategory1 = factory(Category::class)->create(['parent_id' => $category2->id]);
        $subCategory2 = factory(Category::class)->create(['parent_id' => $category2->id]);

        $url = '/api/v1/category';
        $url = sprintf($url);

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertJsonMissing([
                'id'   => $subCategory1->id,
                'name' => $subCategory1->name,
            ])->assertJsonMissing([
                'id'   => $subCategory2->id,
                'name' => $subCategory2->name,
            ]);

        $response->assertJsonFragment([
            'id'   => $category1->id,
            'name' => $category1->name,
        ])->assertJsonFragment([
            'id'   => $category2->id,
            'name' => $category2->name,
        ]);
    }
}
