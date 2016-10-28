<?php
/*
 * This file is part of YourPackage.
 *
 * (c) {{ author }}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    /**
     * Test actor.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Sign in an user if it's the case.
     *
     * @param User|null $user
     *
     * @return \App\Tests\AbstractTestCase
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

    /**
     * Set up the needed configuration to be able to run the tests.
     *
     * @return \App\Tests\AbstractTestCase
     */
    protected function setupConfig()
    {
        // load environment
        $env = $this->app->environment();

        // register Classes
        // $repo = $this->app->make(Repository::class);
        // $cache = $this->app->make(Cache::class);

        // set config values
        //$this->app->config->set('setting', $settings);

        return $this;
    }
}
