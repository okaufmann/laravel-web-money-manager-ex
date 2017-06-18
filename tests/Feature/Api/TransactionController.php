<?php

namespace Tests\Feature\Api;

use App\Models\Transaction;
use Tests\Features\TestCase;

class TransactionController extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function it_can_get_all_transactions()
    {
        // Arrange
        $url = '/api/v1/transactions';
        $transaction = factory(Transaction::class)->create();

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id'                => $transaction->id,
                'status'            => [
                    'id'   => $transaction->status->id,
                    'name' => $transaction->status->name,
                    'slug' => $transaction->status->slug
                ],
                'type'              => [
                    'id'   => $transaction->type->id,
                    'name' => $transaction->type->name,
                    'slug' => $transaction->type->slug
                ],
                'account_name'      => $transaction->account_name,
                'to_account_name'   => $transaction->to_account_name,
                'payee_name'        => $transaction->payee_name,
                'category_name'     => $transaction->category_name,
                'sub_category_name' => $transaction->sub_category_name,
                'amount'            => round($transaction->amount, 2),
                'notes'             => $transaction->notes,
                'created_at'        => $transaction->created_at->toIso8601String(),
                'updated_at'        => $transaction->updated_at->toIso8601String()
            ]);
    }
}
