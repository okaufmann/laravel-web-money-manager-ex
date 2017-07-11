<?php

namespace Tests\Feature;

use Tests\Features\FeatureTestCase;

class TransactionControllerTest extends FeatureTestCase
{
    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function it_can_browse_transaction_create_page()
    {
        // Arrange

        // Act
        $this->ensureAuthenticated();
        $response = $this->get('/');

        // Assert
        $response->assertStatus(200)
            ->assertSee('Add Transaction');
    }
}
