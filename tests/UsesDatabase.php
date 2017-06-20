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

        try {
            unlink($this->database);
            echo "unlinked db file! ".PHP_EOL;
        } catch (\Exception $e) {
            echo "cant unlink db file! ".$e->getMessage().PHP_EOL;
        }

        touch($this->database);
        echo "touch".PHP_EOL;
    }

    public function setUpDatabase(callable $afterMigrations = null)
    {
        if (static::$migrated) {
            return;
        }

        $this->artisan('migrate');
        echo "migrated! ".PHP_EOL;

        $this->app[Kernel::class]->setArtisan(null);

        if ($afterMigrations) {
            $afterMigrations();
        }

        static::$migrated = true;
    }

    /**
     * Handle database transactions on the specified connections.
     *
     * @return void
     */
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
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }
}