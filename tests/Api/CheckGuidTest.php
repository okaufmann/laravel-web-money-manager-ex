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
    public function testGuidLogin()
    {
        $this->get('/services.php?check_guid')
            ->see(Constants::$operation_succeded)
            ->assertResponseOk(200);
    }
}
