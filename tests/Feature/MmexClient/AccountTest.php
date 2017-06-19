<?php

namespace Tests\Feature\MmexClient;

use App\Models\Account;

class AccountTest extends MmexTestCase
{
    public function testDeleteAllAccounts()
    {
        // Arrange
        $account = factory(Account::class)->create();
        $url = $this->buildUrl('', ['delete_bankaccount' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseMissing('accounts', ['name' => $account->name]);
    }

    public function testImportAccounts()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Accounts" : [ { "AccountName" : "Creditcard" }, { "AccountName" : "Private Account" } ] }'];
        $url = $this->buildUrl('', ['import_bankaccount' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->seeSuccess($response);
        $this->assertDatabaseHas('accounts', ['name' => 'Creditcard']);
        $this->assertDatabaseHas('accounts', ['name' => 'Private Account']);
    }
}
