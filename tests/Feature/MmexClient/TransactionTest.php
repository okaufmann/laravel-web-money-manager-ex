<?php
/**
 * MmexControllerTest.php, laravel-money-manager-ex.
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 *
 * @version 1.0
 */

namespace Tests\Feature\MmexClient;

use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionTest extends MmexTestCase
{
    /** @test */
    public function it_returns_an_empty_response_if_no_transactions_does_exist()
    {
        // Arrange
        $url = $this->buildUrl(['download_transaction' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($response->getContent(), '');
    }

    /** @test */
    public function it_can_delete_transactions()
    {
        // Arrange
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);
        $url = $this->buildUrl(['delete_group' => $transaction->id]);

        // Act
        $response = $this->get($url);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertIsSoftDeletedInDatabase('transactions', ['user_id' => $this->user->id, 'id' => $transaction->id]); // must not be deleted! just soft deleted.
    }

    /** @test */
    public function it_can_download_transactions()
    {
        // Arrange
        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);
        $this->addReceiptsToTransaction($transaction);
        $url = $this->buildUrl(['download_transaction' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment(
                [
                    'ID' => (string)$transaction->id,
                    'Date' => $transaction->transaction_date->toDateString(),
                    'Account' => $transaction->account_name,
                    'ToAccount' => $transaction->to_account_name,
                    'Status' => $transaction->status->slug,
                    'Type' => $transaction->type->name,
                    'Payee' => $transaction->payee_name,
                    'Category' => $transaction->category_name,
                    'SubCategory' => $transaction->sub_category_name,
                    'Amount' => (string)$transaction->amount,
                    'Notes' => $transaction->notes,
                    'Attachments' => 'Transaction_'.$transaction->id.'_test-receipt.png;Transaction_'.$transaction->id
                        .'_test-receipt-2.png;Transaction_'.$transaction->id.'_test-receipt-3.png',
                ]
            );
    }


    /** @test */
    public function it_can_download_attachments()
    {
        // Arrange
        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create(['user_id' => $this->user->id]);
        $this->addReceiptsToTransaction($transaction);
        
        // Attachment file name will be provided as comma separated list in the transaction download.
        $fileName = 'Transaction_'.$transaction->id.'_test-receipt-3.png';
        $url = $this->buildUrl(['download_attachment' => $fileName]);

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertHeader('Content-Type', '')
            ->assertHeader('Cache-Control', 'public')
            ->assertHeader('Content-Description', 'File Transfer')
            ->assertHeader('Content-Disposition', 'attachment; filename= '.$fileName)
            ->assertHeader('Content-Transfer-Encoding', 'binary');
    }

    /**
     * @param $transaction
     */
    protected function addReceiptsToTransaction(Transaction $transaction)
    {
        $transactionService = resolve(TransactionService::class);

        $transactionService->addAttachment($transaction, base_path('tests/data/test-receipt.png'), true);
        $transactionService->addAttachment($transaction, base_path('tests/data/test-receipt-2.png'), true);
        $transactionService->addAttachment($transaction, base_path('tests/data/test-receipt-3.png'), true);
    }
}
