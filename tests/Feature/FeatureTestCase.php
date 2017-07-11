<?php

namespace Tests\Features;

use App\Models\User;
use Tests\UsesDatabase;
use Tests\utils\DbUtils;
use UsersTableSeeder;

abstract class FeatureTestCase extends \Tests\TestCase
{
    use UsesDatabase;
    use DbUtils;

    /** @var User */
    public $user;

    public function setUp()
    {
        $this->prepareDatabase();

        parent::setUp();

        $this->setUpDatabase(function () {
            //$this->artisan('db:seed', ['--class' => UsersTableSeeder::class]);
        });

        $this->beginDatabaseTransaction();

        $this->ensureUser();
    }

    /**
     * Create and return a new user.
     *
     * @param array $properties
     *
     * @return \App\Models\User
     */
    protected function ensureUser($properties = [])
    {
        $this->user = factory(User::class)->create($properties);
    }

    protected function ensureAuthenticated()
    {
        $this->ensureUser();
        $this->actingAs($this->user);
        $this->actingAs($this->user, 'api');
    }
}
