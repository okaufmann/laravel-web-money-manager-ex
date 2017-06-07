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

namespace Tests\Feature\Api;

use App\Models\Transaction;

class TransactionTest extends AbstractMmexTestCase
{
    public function testDeleteTransactions()
    {
        // Arrange
        $transaction = factory(Transaction::class)->create();
        $url = $this->buildUrl('', ['delete_group' => $transaction->id]);

        // Act
        $response = $this->get($url);

        // Assert
        $this->seeSuccess($response);
        $this->seeIsSoftDeletedInDatabase('transactions', ['payee_name' => $transaction->payee_name]); // must not be deleted! just soft deleted.
    }

    public function testDownloadTransactions()
    {
        // Arrange
        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create();
        $this->addReceiptsToTransaction($transaction);
        $url = $this->buildUrl('', ['download_transaction' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment(
                [
                    'ID'          => $transaction->id,
                    'Date'        => $transaction->date,
                    'Account'     => $transaction->account_name,
                    'ToAccount'   => $transaction->to_account_name,
                    'Status'      => $transaction->status->slug,
                    'Type'        => $transaction->type->name,
                    'Payee'       => $transaction->payee_name,
                    'Category'    => $transaction->category_name,
                    'SubCategory' => $transaction->sub_category_name,
                    'Amount'      => (string) $transaction->amount,
                    'Notes'       => $transaction->notes,
                    'Attachments' => 'Transaction_'.$transaction->id.'_test-receipt.png;Transaction_'.$transaction->id
                        .'_test-receipt-2.png;Transaction_'.$transaction->id.'_test-receipt-3.png',
                ]
            );
    }

    /**
     * Attachment file name will be provided as comma separated list in the transaction download.
     */
    public function testDownloadAttachment()
    {
        // Arrange
        /** @var Transaction $transaction */
        $transaction = factory(Transaction::class)->create();
        $this->addReceiptsToTransaction($transaction);
        $fileName = 'Transaction_'.$transaction->id.'_test-receipt-3.png';
        $url = $this->buildUrl('', ['download_attachment' => $fileName]);

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
        $transaction->addAttachment(base_path('tests/data/test-receipt.png'), true);
        $transaction->addAttachment(base_path('tests/data/test-receipt-2.png'), true);
        $transaction->addAttachment(base_path('tests/data/test-receipt-3.png'), true);
    }
}
