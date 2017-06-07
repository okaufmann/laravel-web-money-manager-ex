<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Sign in an user if it's the case.
     *
     * @param User|null $user
     *
     * @return $this
     */
    protected function signIn(User $user = null)
    {
        $this->user = $user ?: $this->createUser();
        $this->be($this->user);

        return $this;
    }

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
}
