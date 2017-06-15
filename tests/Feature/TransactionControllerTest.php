<?php

namespace Tests\Feature;

use Tests\Features\TestCase;
use Tests\UsesDatabase;
use Tests\utils\DbUtils;

class TransactionControllerTest extends TestCase
{
    use UsesDatabase;
    use DbUtils;

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
        $response = $this->get('/transactions/create');

        // Assert
        $response->assertStatus(200)
            ->assertSee('Add new Transaction');
    }
}
