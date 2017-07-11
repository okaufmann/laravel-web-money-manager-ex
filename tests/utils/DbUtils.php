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

trait DbUtils
{
    /**
     * Assert that a given where condition does not matches a soft deleted record
     * From: http://stackoverflow.com/questions/33117746/laravel-unit-testing-how-to-seeindatabase-soft-deleted-row.
     *
     * @param string $table
     * @param array  $data
     * @param string $connection
     *
     * @return $this
     */
    protected function assertSeeIsNotSoftDeletedInDatabase($table, array $data, $connection = null)
    {
        $database = $this->app->make('db');

        $connection = $connection ?: $database->getDefaultConnection();

        $count = $database->connection($connection)
            ->table($table)
            ->where($data)
            ->whereNull('deleted_at')
            ->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Found unexpected records in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }

    /**
     * Assert that a given where condition matches a soft deleted record
     * From: http://stackoverflow.com/questions/33117746/laravel-unit-testing-how-to-seeindatabase-soft-deleted-row.
     *
     * @param string $table
     * @param array  $data
     * @param string $connection
     *
     * @return $this
     */
    protected function assertIsSoftDeletedInDatabase($table, array $data, $connection = null)
    {
        $database = $this->app->make('db');

        $connection = $connection ?: $database->getDefaultConnection();

        $count = $database->connection($connection)
            ->table($table)
            ->where($data)
            ->whereNotNull('deleted_at')
            ->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Found unexpected records in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }
}
