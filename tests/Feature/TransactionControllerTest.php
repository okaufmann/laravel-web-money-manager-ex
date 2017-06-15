<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\utils\DbUtils;

class TransactionControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DbUtils;

    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function it_can_browse_transaction_create_form()
    {
        // Arrange

        // Act
        $response = $this->get('/transactions/create');

        // Assert
        $response->assertStatus(200)
            ->assertSee('Add new Transaction');
    }
}
