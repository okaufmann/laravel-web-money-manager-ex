<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 28.10.2016
 * Time: 01:42.
 */

namespace Tests\Feature\Api;

use App\Constants;

class CheckGuidTest extends MmexTestCase
{
    public function testCorrectGuidLogin()
    {
        $this->get('/services.php?check_guid&guid='.$this->guid)
            ->assertSee(Constants::$operation_succeded)
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertStatus(200);
    }

    public function testIncorrectGuidLogin()
    {
        $incorrectGuid = '{DE43-D62C-A609-UIFSDDFUISF}';

        $this->get('/services.php?check_guid&guid='.$incorrectGuid)
            ->assertSee(Constants::$wrong_guid)
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertStatus(200); // need to be 200 cause client otherwise crash..
    }
}
