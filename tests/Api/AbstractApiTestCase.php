<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 28.10.2016
 * Time: 01:23.
 */
namespace App\Tests\Api;

use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class AbstractApiTestCase extends AbstractTestCase
{
    use DatabaseMigrations;
}
