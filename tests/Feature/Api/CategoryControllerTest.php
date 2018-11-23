<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Category;
use Tests\Features\FeatureTestCase;

class CategoryControllerTest extends FeatureTestCase
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
        $this->ensureAuthenticated();
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
    public function it_can_access_just_root_categories_of_a_user()
    {
        // Arrange
        $category1 = factory(Category::class)->create(['user_id' => $this->user->id]);
        $category2 = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subCategory1 = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category2->id]);
        $subCategory2 = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category2->id]);

        $anotherUser = factory(User::class)->create();
        $category3 = factory(Category::class)->create(['user_id' => $anotherUser->id]);
        $category4 = factory(Category::class)->create(['user_id' => $anotherUser->id]);

        $url = '/api/v1/category';

        // Act
        $this->ensureAuthenticated();
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200);

        // no sub categories
        $response->assertJsonMissing([
            'id'   => $subCategory1->id,
            'name' => $subCategory1->name,
        ])->assertJsonMissing([
            'id'   => $subCategory2->id,
            'name' => $subCategory2->name,
        ]);

        // no categories of other users
        $response->assertJsonMissing([
            'id'   => $category3->id,
            'name' => $category3->name,
        ])->assertJsonMissing([
            'id'   => $category4->id,
            'name' => $category4->name,
        ]);

        // just root categories
        $response->assertJsonFragment([
            'id'   => $category1->id,
            'name' => $category1->name,
        ])->assertJsonFragment([
            'id'   => $category2->id,
            'name' => $category2->name,
        ]);
    }
}
