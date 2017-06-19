<?php

namespace Tests\Features;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\UsesDatabase;
use Tests\utils\DbUtils;
use UsersTableSeeder;

abstract class TestCase extends \Tests\TestCase
{
    //use UsesDatabase;
    use DatabaseMigrations;
    use DbUtils;

    public function setUp()
    {
        //$this->prepareDatabase(true);

        parent::setUp();

        //$this->setUpDatabase(function () {
        //    $this->artisan('db:seed', ['--class' => UsersTableSeeder::class]);
        //});

        //$this->beginDatabaseTransaction();
    }
}
