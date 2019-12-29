<?php

namespace Tests\Feature;

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
    /** @test */
    public function it_can_create_a_transaction_with_all_properties()
    {
        // Arrange
        $status = factory(TransactionStatus::class)->create();
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        // TODO: Test fileupload
        //Storage::fake('media');
        $data = [
            'transaction_date'   => '12/31/2017',
            'transaction_status' => $status->id,
            'transaction_type'   => $type->id,
            'account'            => $account->id,
            'payee'              => $payee->id,
            'category'           => $category->id,
            'subcategory'        => $subcategory->id,
            'amount'             => 13.37,
            'notes'              => 'Some notes',
            //'attachments'        => [UploadedFile::fake()->image('receipt.jpg')]
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->post('/transactions', $data);

        // Assert
        $response->assertRedirect('/');

        $this->assertDatabaseHas('transactions', [
            'user_id'           => $this->user->id,
            'transaction_date'  => Carbon::create(2017, 12, 31, 0, 0, 0)->toDateTimeString(),
            'status_id'         => $status->id,
            'type_id'           => $type->id,
            'account_name'      => $account->name,
            'to_account_name'   => null,
            'payee_name'        => $payee->name,
            'sub_category_name' => $subcategory->name,
            'amount'            => 13.37,
            'notes'             => 'Some notes',
        ]);

        //$lastTransaction = Transaction::latest()->get()->first();
        //$filename = 'Transaction_'.$lastTransaction->id.'_receipt.png';
        //Storage::disk('media')->assertExists($lastTransaction->id.'/'.$filename);
    }

    /** @test */
    public function it_can_edit_an_existing_transaction()
    {
        // Arrange
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);

        $status = factory(TransactionStatus::class)->create();
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        // TODO: Test fileupload
        //Storage::fake('media');
        $data = [
            'transaction_date'   => '12/31/2017',
            'transaction_status' => $status->id,
            'transaction_type'   => $type->id,
            'account'            => $account->id,
            'payee'              => $payee->id,
            'category'           => $category->id,
            'subcategory'        => $subcategory->id,
            'amount'             => 13.37,
            'notes'              => 'Some notes',
            //'attachments'        => [UploadedFile::fake()->image('receipt.jpg')]
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->put('/transactions/'.$transaction->id, $data);

        // Assert
        $response->assertRedirect('/');

        $this->assertDatabaseHas('transactions', [
            'id'                => $transaction->id,
            'user_id'           => $this->user->id,
            'transaction_date'  => Carbon::create(2017, 12, 31, 0, 0, 0)->toDateTimeString(),
            'status_id'         => $status->id,
            'type_id'           => $type->id,
            'account_name'      => $account->name,
            'to_account_name'   => null,
            'payee_name'        => $payee->name,
            'category_name'     => $category->name,
            'sub_category_name' => $subcategory->name,
            'amount'            => 13.37,
            'notes'             => 'Some notes',
        ]);

        //$lastTransaction = Transaction::latest()->get()->first();
        //$filename = 'Transaction_'.$lastTransaction->id.'_receipt.png';
        //Storage::disk('media')->assertExists($lastTransaction->id.'/'.$filename);
    }

    /** @test */
    public function it_stores_last_used_category_by_payee_in_payees_table_on_create()
    {
        // Arrange
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->post('/transactions', $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'id' => $payee->id, 'last_category_id' => $category->id]);
    }

    /** @test */
    public function it_stores_last_used_category_by_payee_in_payees_table_on_update()
    {
        // Arrange
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);

        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->put('/transactions/'.$transaction->id, $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'id' => $payee->id, 'last_category_id' => $category->id]);
    }

    /** @test */
    public function it_stores_last_used_subcategory_by_payee_in_payees_table_on_create()
    {
        // Arrange
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'subcategory'      => $subcategory->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->post('/transactions', $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'id' => $payee->id, 'last_category_id' => $subcategory->id]);
    }

    /** @test */
    public function it_stores_last_used_subcategory_by_payee_in_payees_table_on_update()
    {
        // Arrange
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);

        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'subcategory'      => $subcategory->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->put('/transactions/'.$transaction->id, $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'id' => $payee->id, 'last_category_id' => $subcategory->id]);
    }

    /** @test */
    public function it_stores_account_to_on_transfer_transactions()
    {
        // Arrange
        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $toaccount = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'to_account'       => $toaccount->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'subcategory'      => $subcategory->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->post('/transactions', $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('transactions', [
            'user_id'           => $this->user->id,
            'type_id'           => $type->id,
            'account_name'      => $account->name,
            'to_account_name'   => $toaccount->name,
            'payee_name'        => $payee->name,
            'category_name'     => $category->name,
            'sub_category_name' => $subcategory->name,
        ]);
    }

    /** @test */
    public function it_stores_last_used_date_by_payee_in_payees_table_on_create()
    {
        // Arrange
        $knownDate = Carbon::create(2017, 07, 16, 12);
        Carbon::setTestNow($knownDate);

        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'subcategory'      => $subcategory->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->post('/transactions', $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'id' => $payee->id, 'last_used_at' => Carbon::now()->toDateTimeString()]);
    }

    /** @test */
    public function it_stores_last_used_date_by_payee_in_payees_table_on_update()
    {
        // Arrange
        $knownDate = Carbon::create(2017, 07, 16, 12);
        Carbon::setTestNow($knownDate);

        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);

        $type = factory(TransactionType::class)->create();
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $payee = factory(Payee::class)->create(['user_id' => $this->user->id]);
        $category = factory(Category::class)->create(['user_id' => $this->user->id]);
        $subcategory = factory(Category::class)->create(['user_id' => $this->user->id, 'parent_id' => $category->id]);

        $data = [
            'transaction_type' => $type->id,
            'account'          => $account->id,
            'payee'            => $payee->id,
            'category'         => $category->id,
            'subcategory'      => $subcategory->id,
            'amount'           => 13.37,
        ];

        // Act
        $this->ensureAuthenticated();
        $response = $this->put('/transactions/'.$transaction->id, $data);

        // Assert
        $response->assertRedirect('/');
        $this->assertDatabaseHas('payees', ['user_id' => $this->user->id, 'id' => $payee->id, 'last_used_at' => Carbon::now()->toDateTimeString()]);
    }
}
