<?php

namespace Tests\Feature\MmexClient;

use App\Models\Account;

class AccountTest extends MmexTestCase
{
    public function testDeleteAllAccounts()
    {
        // Arrange
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $url = $this->buildUrl(['delete_bankaccount' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDontSeeInDatabase('accounts', ['user_id' => $this->user->id, 'name' => $account->name]);
    }

    public function testImportAccounts()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Accounts" : [ { "AccountName" : "Creditcard" }, { "AccountName" : "Private Account" } ] }'];
        $url = $this->buildUrl(['import_bankaccount' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertSeeInDatabase('accounts', ['user_id' => $this->user->id, 'name' => 'Creditcard']);
        $this->assertSeeInDatabase('accounts', ['user_id' => $this->user->id, 'name' => 'Private Account']);
    }
}
