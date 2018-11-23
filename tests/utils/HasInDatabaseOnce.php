<?php
/*
 * laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 */

namespace Tests\utils;

use Illuminate\Foundation\Testing\Constraints\HasInDatabase;

class HasInDatabaseOnce extends HasInDatabase
{
    private $entry;

    /**
     * Check if the data is found in the given table once and set the entry to private field.
     *
     * @param string $table
     *
     * @return bool
     */
    public function matches($table): bool
    {
        $query = $this->database->table($table)->where($this->data);

        $entries = $query->take(2)->get();
        $this->entry = $entries->first();

        return $entries->count() === 1;
    }

    /**
     * Get the Entry based on the given attributes.
     */
    public function getEntry()
    {
        return $this->entry;
    }
}
