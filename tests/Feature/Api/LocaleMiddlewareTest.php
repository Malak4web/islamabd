<?php

namespace Tests\Feature\Api;

use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_english_content_by_default()
    {
        Service::factory()->create([
            'title_en' => 'Residential',
            'title_ar' => 'سكني',
            'is_active' => true
        ]);

        $this->getJson('/api/v1/services')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Residential');
    }

    public function test_api_returns_arabic_content_with_ar_header()
    {
        Service::factory()->create([
            'title_en' => 'Residential',
            'title_ar' => 'سكني',
            'is_active' => true
        ]);

        $this->getJson('/api/v1/services', ['Accept-Language' => 'ar'])
            ->assertOk()
            ->assertJsonPath('data.0.title', 'سكني');
    }

    public function test_api_falls_back_to_english_for_unsupported_locale()
    {
        Service::factory()->create([
            'title_en' => 'Residential',
            'title_ar' => 'سكني',
            'is_active' => true
        ]);

        $this->getJson('/api/v1/services', ['Accept-Language' => 'fr'])
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Residential');
    }

    public function test_pages_endpoint_returns_localized_title()
    {
        $page = Page::factory()->create([
            'slug' => 'home',
            'title_en' => 'Home',
            'title_ar' => 'الرئيسية'
        ]);

        $this->getJson('/api/v1/pages/home', ['Accept-Language' => 'ar'])
            ->assertOk()
            ->assertJsonPath('data.title', 'الرئيسية');

        $this->getJson('/api/v1/pages/home', ['Accept-Language' => 'en'])
            ->assertOk()
            ->assertJsonPath('data.title', 'Home');
    }

    public function test_settings_address_returns_localized()
    {
        Setting::updateOrCreate(['key' => 'address_en', 'group' => 'contact', 'type' => 'textarea'], ['value' => 'Dubai Office']);
        Setting::updateOrCreate(['key' => 'address_ar', 'group' => 'contact', 'type' => 'textarea'], ['value' => 'مكتب دبي']);

        // When we hit the settings endpoint without language, it just returns a flat object.
        // Wait, the test says: assertJsonPath('data.address','مكتب دبي')
        // We will need to adjust the SettingController to return 'address' dynamically based on locale, or perhaps the test expects that.
        // But the workflow says "Setting::set('address_en','Dubai Office')" and then "assertJsonPath('data.address','مكتب دبي')"
        
        $this->getJson('/api/v1/settings', ['Accept-Language' => 'ar'])
            ->assertOk()
            ->assertJsonPath('data.address', 'مكتب دبي');
    }
}
