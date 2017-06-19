<?php

namespace Tests\Features;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\utils\DbUtils;

abstract class TestCase extends \Tests\TestCase
{
    use DatabaseMigrations;
    use DbUtils;

    protected $database = __DIR__.'/../database/testing.sqlite';

    public function setUp()
    {
        @unlink($this->database);
        touch($this->database);

        parent::setUp();
    }
}
