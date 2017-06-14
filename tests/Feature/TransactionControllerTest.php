<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionControllerTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function it_can_browse_transaction_create_form()
    {
        // Arrange

        // Act
        $response = $this->get('/transactions/create');

        // Assert
        $response->assertStatus(200)
            ->assertSee("Add new Transaction");

    }
}
