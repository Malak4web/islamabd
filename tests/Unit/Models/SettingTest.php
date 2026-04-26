<?php

namespace Tests\Unit\Models;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_has_correct_fillable_fields()
    {
        $setting = new Setting();
        $this->assertEquals(['key', 'value', 'group', 'type'], $setting->getFillable());
    }

    public function test_setting_key_must_be_unique()
    {
        Setting::create(['key' => 'site_name', 'value' => 'InDesign']);
        
        $this->expectException(QueryException::class);
        Setting::create(['key' => 'site_name', 'value' => 'Duplicate']);
    }

    public function test_setting_get_helper_returns_value_by_key()
    {
        Setting::create(['key' => 'phone', 'value' => '+971500000000']);
        $this->assertEquals('+971500000000', Setting::get('phone'));
    }

    public function test_setting_get_returns_default_when_key_missing()
    {
        $this->assertEquals('fallback', Setting::get('missing', 'fallback'));
    }

    public function test_setting_set_creates_or_updates()
    {
        Setting::set('email', 'test@test.com');
        $this->assertDatabaseHas('settings', ['key' => 'email', 'value' => 'test@test.com']);

        Setting::set('email', 'new@test.com');
        $this->assertDatabaseHas('settings', ['key' => 'email', 'value' => 'new@test.com']);
        $this->assertEquals(1, Setting::where('key', 'email')->count());
    }
}
