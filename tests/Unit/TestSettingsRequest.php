<?php

namespace Tests\Unit;

use App\Http\Requests\SettingsRequest;
use Tests\TestCase;

class TestSettingsRequest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     */
    public function it_can_transform_ids_and_values_in_correct_key_value_collection()
    {
        // Arrange
        $keys = collect([1, 22, 33, 49]);
        $values = collect(['a', 'b', 'c', 'd']);

        // Act
        $settingRequest = $this->app->make(SettingsRequest::class);
        list($status, $types) = $settingRequest->getStatusAndTypes();

        // Assert
        $expected = [
            1  => 'a',
            22 => 'b',
            33 => 'c',
            49 => 'd',
        ];

        $this->assertEquals($expected, $status);
        $this->assertEquals($expected, $types);
    }
}
