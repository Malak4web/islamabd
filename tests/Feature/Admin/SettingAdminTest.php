<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->withHeaders([
            'Origin' => 'http://localhost',
            'Accept' => 'application/json',
        ]);
    }

    public function test_unauthenticated_cannot_access_admin_settings()
    {
        $this->getJson('/api/admin/settings')->assertStatus(401);
        $this->putJson('/api/admin/settings/site_name', ['value' => 'New'])->assertStatus(401);
    }

    public function test_admin_can_list_all_settings()
    {
        Setting::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/settings');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'key', 'value', 'group', 'type']
                ]
            ]);
    }

    public function test_admin_can_list_settings_filtered_by_group()
    {
        Setting::factory()->count(3)->create(['group' => 'general']);
        Setting::factory()->count(2)->create(['group' => 'contact']);

        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/settings?group=contact');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_update_setting_by_key()
    {
        Setting::factory()->create(['key' => 'phone_1', 'value' => 'old']);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson('/api/admin/settings/phone_1', [
                'value' => 'new-phone'
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('settings', [
            'key' => 'phone_1',
            'value' => 'new-phone'
        ]);
    }

    public function test_update_setting_requires_value_field()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->putJson('/api/admin/settings/phone_1', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['value']);
    }

    public function test_admin_can_bulk_update_settings()
    {
        Setting::factory()->create(['key' => 'k1', 'value' => 'old1']);
        Setting::factory()->create(['key' => 'k2', 'value' => 'old2']);

        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/settings/bulk', [
                'settings' => [
                    ['key' => 'k1', 'value' => 'new1'],
                    ['key' => 'k2', 'value' => 'new2'],
                ]
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('settings', ['key' => 'k1', 'value' => 'new1']);
        $this->assertDatabaseHas('settings', ['key' => 'k2', 'value' => 'new2']);
    }

    public function test_admin_can_upload_logo_image()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('logo.png', 100, 'image/png');

        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/settings/image/logo', [
                'file' => $file
            ]);


        $response->assertOk()
            ->assertJsonStructure(['data' => ['url']]);
            
        // Check if setting is updated
        $setting = Setting::where('key', 'logo')->first();
        $this->assertNotNull($setting);
        $this->assertStringContainsString('/storage/settings/', $setting->value);
    }
}

