<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var User */
    public $user;

    /**
     * Create and return a new user.
     *
     * @param array $properties
     *
     * @return \App\Models\User
     */
    protected function createUser($properties = [])
    {
        return factory(User::class)->create($properties);
    }

    protected function ensureAuthenticated()
    {
        $this->user = $this->createUser();
        $this->actingAs($this->user);
        $this->actingAs($this->user, 'api');
    }
}
