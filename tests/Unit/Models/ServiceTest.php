<?php

namespace Tests\Unit\Models;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_has_correct_fillable_fields()
    {
        $service = new Service();
        $this->assertEquals([
            'title_en', 'title_ar', 'description_en', 'description_ar', 'icon', 'image', 'gallery', 'order', 'is_active'
        ], $service->getFillable());
    }

    public function test_is_active_cast_to_boolean()
    {
        $service = Service::create([
            'title_en' => 'Test', 'title_ar' => 'اختبار', 
            'description_en' => 'Desc', 'description_ar' => 'وصف',
            'is_active' => 1
        ]);
        $this->assertIsBool($service->is_active);
        $this->assertTrue($service->is_active);
    }

    public function test_scope_active_filters_correctly()
    {
        Service::factory()->count(3)->create(['is_active' => true]);
        Service::factory()->count(2)->create(['is_active' => false]);
        $this->assertEquals(3, Service::active()->count());
    }

    public function test_scope_ordered_sorts_ascending()
    {
        Service::factory()->create(['order' => 3]);
        Service::factory()->create(['order' => 1]);
        Service::factory()->create(['order' => 2]);
        $this->assertEquals(1, Service::ordered()->first()->order);
    }

    public function test_factory_creates_bilingual_fields()
    {
        $service = Service::factory()->create();
        $this->assertNotEmpty($service->title_en);
        $this->assertNotEmpty($service->title_ar);
    }
}
