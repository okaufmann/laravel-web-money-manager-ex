<?php

namespace Tests\Features;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;
use Tests\UsesDatabase;
use Tests\utils\DbUtils;
use UsersTableSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use UsesDatabase;
    use DbUtils;

    public function setUp()
    {
        $this->prepareDatabase();

        parent::setUp();

        $this->setUpDatabase(function () {
            $this->artisan('db:seed', ['--class' => UsersTableSeeder::class]);
        });

        $this->beginDatabaseTransaction();
    }
}
