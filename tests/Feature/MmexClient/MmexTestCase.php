<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 28.10.2016
 * Time: 01:23.
 */

namespace Tests\Feature\MmexClient;

use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\Features\FeatureTestCase;

abstract class MmexTestCase extends FeatureTestCase
{
    protected $apiUri = '/services.php';
    protected $success = 'Operation has succeeded';

    protected function buildUrl(array $data): string
    {
        $data['guid'] = $this->user->mmex_guid;
        $paramString = http_build_query($data);

        return $this->apiUri.'?'.$paramString;
    }

    /**
     * @param TestResponse $response
     *
     * @return TestResponse
     */
    protected function assertSeeMmexSuccess(TestResponse $response)
    {
        return $response->assertStatus(200)
            ->assertSee($this->success);
    }
}
