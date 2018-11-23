<?php

namespace Tests\Feature\MmexClient;

use App\Models\Account;

class AccountTest extends MmexTestCase
{
    /** @test */
    public function it_can_delete_all_accounts()
    {
        // Arrange
        $account = factory(Account::class)->create(['user_id' => $this->user->id]);
        $url = $this->buildUrl(['delete_bankaccount' => 'true']);

        // Act
        $response = $this->get($url);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseMissing('accounts', ['user_id' => $this->user->id, 'name' => $account->name]);
    }

    /** @test */
    public function it_can_import_accounts()
    {
        // Arrange
        $data = ['MMEX_Post' => '{ "Accounts" : [ { "AccountName" : "Creditcard" }, { "AccountName" : "Private Account" } ] }'];
        $url = $this->buildUrl(['import_bankaccount' => 'true']);

        // Act
        $response = $this->postJson($url, $data);

        // Assert
        $this->assertSeeMmexSuccess($response);
        $this->assertDatabaseHas('accounts', ['user_id' => $this->user->id, 'name' => 'Creditcard']);
        $this->assertDatabaseHas('accounts', ['user_id' => $this->user->id, 'name' => 'Private Account']);
    }
}
