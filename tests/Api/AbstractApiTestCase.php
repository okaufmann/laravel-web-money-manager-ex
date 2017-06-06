<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 28.10.2016
 * Time: 01:23.
 */

namespace App\Tests\Api;

use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class AbstractApiTestCase extends AbstractTestCase
{
    use DatabaseMigrations;

    protected $apiUri = '/services.php';
    protected $guid = '{D6A33C24-DE43-D62C-A609-EF5138F33F30}';
    protected $success = 'Operation has succeeded';

    protected function buildUrl(string $url, array $data) : string
    {
        $data['guid'] = $this->guid;
        $paramString = http_build_query($data);

        return $this->apiUri.'?'.$paramString;
    }

    protected function seeSuccess()
    {
        return $this->seeStatusCode(200)
            ->see($this->success);
    }

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
    protected function seeIsNotSoftDeletedInDatabase($table, array $data, $connection = null)
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
    protected function seeIsSoftDeletedInDatabase($table, array $data, $connection = null)
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
