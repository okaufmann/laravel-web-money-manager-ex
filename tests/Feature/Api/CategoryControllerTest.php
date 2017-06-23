<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic test example.
     * @test
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
}
