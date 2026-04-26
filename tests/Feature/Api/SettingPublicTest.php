<?php

namespace Tests\Feature\Api;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_get_all_settings()
    {
        Setting::factory()->create([
            'key' => 'phone_1',
            'value' => '+971 4 123 4567'
        ]);

        Setting::factory()->create([
            'key' => 'site_name',
            'value' => 'InDesign'
        ]);

        $response = $this->getJson('/api/v1/settings');

        $response->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonPath('data.phone_1', '+971 4 123 4567')
            ->assertJsonPath('data.site_name', 'InDesign');
    }

    public function test_settings_response_is_flat_key_value_object()
    {
        Setting::factory()->create(['key' => 'k1', 'value' => 'v1']);
        Setting::factory()->create(['key' => 'k2', 'value' => 'v2']);

        $response = $this->getJson('/api/v1/settings');

        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertArrayHasKey('k1', $data);
        $this->assertArrayHasKey('k2', $data);
        $this->assertEquals('v1', $data['k1']);
    }

    public function test_settings_endpoint_is_publicly_accessible_no_auth()
    {
        $response = $this->getJson('/api/v1/settings');
        $response->assertOk();
    }
}
