<?php

namespace Tests\Feature;

use Tests\Features\FeatureTestCase;

class SettingsControllerTest extends FeatureTestCase
{
    /** @test */
    public function it_can_browse_settings_page()
    {
        // Arrange
        $url = '/settings';

        // Act
        $this->ensureAuthenticated();
        $response = $this->get($url);

        // Assert
        $response->assertStatus(200)
            ->assertSee('Settings');
    }
}
