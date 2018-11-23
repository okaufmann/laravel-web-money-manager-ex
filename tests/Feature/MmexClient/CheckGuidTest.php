<?php

namespace Tests\Feature\MmexClient;

use App\Services\Mmex\MmexConstants;

class CheckGuidTest extends MmexTestCase
{
    /** @test */
    public function it_can_allow_correct_guids()
    {
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
}
