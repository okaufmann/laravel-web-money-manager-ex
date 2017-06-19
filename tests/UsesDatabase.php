<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait UsesDatabase
{
    /** @var string */
    protected $database = __DIR__.'/../database/testing.sqlite';

    /** @var bool */
    protected static $migrated = false;

    public function prepareDatabase($force = false)
    {
        // The database needs to be deleted before the application gets boted
        // to avoid having the database in a weird read-only state.

        if (!$force && static::$migrated) {
            return;
        }

        @unlink($this->database);
        touch($this->database);
        \Log::debug('prepared testing database');
    }

    public function setUpDatabase(callable $afterMigrations = null)
    {
        if (static::$migrated) {
            return;
        }

        $this->artisan('migrate');

        $this->app[Kernel::class]->setArtisan(null);

        if ($afterMigrations) {
            $afterMigrations();
        }

        static::$migrated = true;
        \Log::debug('setup (migrated and seeded) database');
    }

    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $database->connection($name)->rollBack();
            }
        });
        \Log::debug('begun db transaction');
    }

    protected function connectionsToTransact(): array
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }
}
