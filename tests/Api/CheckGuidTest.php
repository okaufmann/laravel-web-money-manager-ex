<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 28.10.2016
 * Time: 01:42.
 */
namespace App\Tests\Api;

use App\Constants;

class CheckGuidTest extends AbstractApiTestCase
{
    public function testCorrectGuidLogin()
    {
        $this->get('/services.php?check_guid&guid='.$this->guid)
            ->see(Constants::$operation_succeded)
            ->seeHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertResponseOk(200);
    }

    public function testIncorrectGuidLogin()
    {
        $incorrectGuid = '{DE43-D62C-A609-UIFSDDFUISF}';

        $this->get('/services.php?check_guid&guid='.$incorrectGuid)
            ->see(Constants::$wrong_guid)
            ->seeHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertResponseOk(200); // need to be 200 cause client otherwise crash..
    }
}
