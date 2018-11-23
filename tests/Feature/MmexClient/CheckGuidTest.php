<?php

namespace Tests\Feature\MmexClient;

use Log;
use App\Services\Mmex\MmexConstants;

class CheckGuidTest extends MmexTestCase
{
    /** @test */
    public function it_can_allow_correct_guids()
    {
        Log::debug('current user is', ['user' => $this->user]);

        $this->get('/services.php?check_guid&guid='.$this->user->mmex_guid)
            ->assertSee(MmexConstants::$operation_succeded)
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertStatus(200);
    }

    /** @test */
    public function it_can_deny_incorrect_guids()
    {
        $incorrectGuid = '{DE43-D62C-A609-UIFSDDFUISF}';

        $this->get('/services.php?check_guid&guid='.$incorrectGuid)
            ->assertSee(MmexConstants::$wrong_guid)
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertStatus(200); // need to be 200 cause client otherwise crash..
    }

    /** @test */
    public function it_can_deny_empty_guids()
    {
        $emptyGuid = '';

        $this->get('/services.php?check_guid&guid='.$emptyGuid)
            ->assertSee(MmexConstants::$wrong_guid)
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertStatus(200); // need to be 200 cause client otherwise crash..
    }
}
