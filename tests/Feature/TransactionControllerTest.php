<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\Features\FeatureTestCase;

class TransactionControllerTest extends FeatureTestCase
{
    /**
     * @test
     */
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

        // Assert the file was stored...
        $this->assertDatabaseHas('transactions', [
            'user_id'           => $this->user->id,
            'transaction_date'  => Carbon::create(2017, 12, 31),
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

    /**
     * @test
     */
    public function it_store_last_used_category_by_payee_in_payees_table()
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
        $this->assertDatabaseHas('payees', ['id' => $payee->id, 'last_category_id' => $category->id]);
    }

    /**
     * @test
     */
    public function it_store_last_used_subcategory_by_payee_in_payees_table()
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
        $this->assertDatabaseHas('payees', ['id' => $payee->id, 'last_category_id' => $subcategory->id]);
    }
}
