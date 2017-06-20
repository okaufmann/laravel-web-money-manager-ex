<?php

namespace Tests\Features;

use Tests\UsesDatabase;
use Tests\utils\DbUtils;
use UsersTableSeeder;

abstract class TestCase extends \Tests\TestCase
{
    use UsesDatabase;
    use DbUtils;

    public function setUp()
    {
        $this->prepareDatabase();

        parent::setUp();

        $this->setUpDatabase(function () {
            //$this->artisan('db:seed', ['--class' => UsersTableSeeder::class]);
        });

        $this->beginDatabaseTransaction();
    }
}
