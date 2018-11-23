<?php

namespace Tests\Features;

use App\Models\User;
use Tests\utils\DbUtils;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class FeatureTestCase extends \Tests\TestCase
{
    use RefreshDatabase;
    use DbUtils;

    /** @var User */
    public $user;

    public function setUp()
    {
        parent::setUp();

        $this->ensureUser();
    }

    /**
     * Create and return a new user.
     *
     * @param array $properties
     */
    protected function ensureUser($properties = [])
    {
        if (! $this->user) {
            $this->user = count($properties) ? factory(User::class)->create($properties) : factory(User::class)->create();
        }
    }

    protected function ensureAuthenticated()
    {
        $this->ensureUser();
        $this->actingAs($this->user);
        $this->actingAs($this->user, 'api');
    }
}
