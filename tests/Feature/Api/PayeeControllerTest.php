<?php

namespace Tests\Feature\Api;

use App\Models\Payee;
use App\Models\User;
use Tests\Features\FeatureTestCase;

class PayeeControllerTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function it_can_access_all_payees_of_user()
    {
        // Arrange
        $payee1 = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $payee2 = factory(Payee::class)->create(['user_id' => $this->user->id]);

        $anotherUser = factory(User::class)->create();
        $payee3 = factory(Payee::class)->create(['user_id' => $anotherUser->id]);
        $payee4 = factory(Payee::class)->create(['user_id' => $anotherUser->id]);

        $url = '/api/v1/payee';

        // Act
        $this->ensureAuthenticated();
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200);

        // no payees of other users
        $response->assertJsonMissing([
            'id'   => $payee3->id,
            'name' => $payee3->name,
        ])->assertJsonMissing([
            'id'   => $payee4->id,
            'name' => $payee4->name,
        ]);

        // just user's payees
        $response->assertJsonFragment([
            'id'   => $payee1->id,
            'name' => $payee1->name,
        ])->assertJsonFragment([
            'id'   => $payee2->id,
            'name' => $payee2->name,
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_new_payees_from_kendoui_control()
    {
        // Arrange
        $url = '/api/v1/payee';

        $data = ['name' => '7-Eleven'];

        // Act
        $this->ensureAuthenticated();
        $response = $this->post($url, $data);

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('payees', ['name' => '7-Eleven']);

    }
}
