<?php

namespace Tests\Feature\Api;

use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Carbon\Carbon;
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
    public function it_can_get_all_transactions()
    {
        // Arrange
        $url = '/api/v1/transactions';
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);

        // Act
        $this->ensureAuthenticated();
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id'                => $transaction->id,
                'status'            => [
                    'id'   => $transaction->status->id,
                    'name' => 'mmex.'.$transaction->status->name,
                    'slug' => $transaction->status->slug,
                ],
                'type'              => [
                    'id'   => $transaction->type->id,
                    'name' => 'mmex.'.$transaction->type->name,
                    'slug' => $transaction->type->slug,
                ],
                'account_name'      => $transaction->account_name,
                'to_account_name'   => $transaction->to_account_name,
                'payee_name'        => $transaction->payee_name,
                'category_name'     => $transaction->category_name,
                'sub_category_name' => $transaction->sub_category_name,
                'amount'            => round($transaction->amount, 2),
                'notes'             => $transaction->notes,
                'created_at'        => $transaction->created_at->toIso8601String(),
                'updated_at'        => $transaction->updated_at->toIso8601String(),
            ]);
    }

    /** @test */
    public function it_can_create_new_transaction_with_a_new_payee()
    {
        // Arrange
        $knownDate = Carbon::create(2017, 07, 16);
        Carbon::setTestNow($knownDate);

        $url = '/api/v1/transactions';

        $status = factory(TransactionStatus::class)->create();
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);

        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_date'   => $knownDate->toIso8601String(),
            'transaction_status' => $status->name,
            'transaction_type'   => $type->name,
            'account'            => $account->name,
            'payee'              => 'a new payee',
            'category'           => $category->name,
            'sub_category'       => $subcategory->name,
            'amount'             => 13.37,
            'notes'              => 'Created from API',
        ];
        //dd($data);

        // Act
        $this->ensureAuthenticated();
        $response = $this->json('POST', $url, $data);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('payees', [
            'user_id'          => $this->user->id,
            'name'             => 'a new payee',
            'last_used_at'     => null,
            'last_category_id' => null,
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id'           => $this->user->id,
            'transaction_date'  => Carbon::create(2017, 07, 16, 0, 0, 0)->toDateTimeString(),
            'status_id'         => $status->id,
            'type_id'           => $type->id,
            'account_name'      => $account->name,
            'to_account_name'   => null,
            'payee_name'        => 'a new payee',
            'sub_category_name' => $subcategory->name,
            'amount'            => 13.37,
            'notes'             => 'Created from API',
        ]);
    }

    /** @test */
    public function it_can_create_a_new_transaction_with_a_existing_payee()
    {
        // Arrange
        $knownDate = Carbon::create(2017, 07, 16);
        Carbon::setTestNow($knownDate);

        $url = '/api/v1/transactions';

        $status = factory(TransactionStatus::class)->create();
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_date'   => $knownDate->toIso8601String(),
            'transaction_status' => $status->name,
            'transaction_type'   => $type->name,
            'account'            => $account->name,
            'payee'              => $payee->name,
            'category'           => $category->name,
            'sub_category'       => $subcategory->name,
            'amount'             => 13.37,
            'notes'              => 'Created from API',
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->json('POST', $url, $data);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('payees', [
            'user_id'          => $this->user->id,
            'name'             => $payee->name,
            'last_used_at'     => $payee->last_user_at,
            'last_category_id' => $payee->last_category_id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id'           => $this->user->id,
            'transaction_date'  => Carbon::create(2017, 07, 16, 0, 0, 0)->toDateTimeString(),
            'status_id'         => $status->id,
            'type_id'           => $type->id,
            'account_name'      => $account->name,
            'to_account_name'   => null,
            'payee_name'        => $payee->name,
            'sub_category_name' => $subcategory->name,
            'amount'            => 13.37,
            'notes'             => 'Created from API',
        ]);
    }
}
