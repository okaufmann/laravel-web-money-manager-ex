<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 28.10.2016
 * Time: 01:23.
 */

namespace Tests\Feature\MmexClient;

use Illuminate\Foundation\Testing\TestResponse;
use Tests\Features\TestCase;

abstract class MmexTestCase extends TestCase
{
    protected $apiUri = '/services.php';
    protected $guid = '{D6A33C24-DE43-D62C-A609-EF5138F33F30}';
    protected $success = 'Operation has succeeded';

    protected function buildUrl(string $url, array $data): string
    {
        $data['guid'] = $this->guid;
        $paramString = http_build_query($data);

        return $this->apiUri.'?'.$paramString;
    }

    /**
     * @param TestResponse $response
     *
     * @return TestResponse
     */
    protected function seeSuccess(TestResponse $response)
    {
        return $response->assertStatus(200)
            ->assertSee($this->success);
    }
}
