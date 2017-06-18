<?php

namespace Tests\Feature;


use Tests\Features\TestCase;

class SettingsControllerTest extends TestCase
{

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function it_can_browse_settings_page()
    {
        // Arrange
        $url = '/settings';

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertSee("Settings");
    }
}
