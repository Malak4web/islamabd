<?php

namespace Tests\Feature\Api;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicePublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders([
            'Origin' => 'http://localhost',
            'Accept' => 'application/json',
        ]);
    }

    public function test_can_get_list_of_active_services()
    {
        Service::factory()->count(4)->create(['is_active' => true]);
        Service::factory()->count(2)->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/services');

        $response->assertOk()
            ->assertJsonCount(4, 'data');
    }

    public function test_services_returned_in_order()
    {
        Service::factory()->create(['title_en' => 'Last', 'order' => 3]);
        Service::factory()->create(['title_en' => 'First', 'order' => 1]);
        Service::factory()->create(['title_en' => 'Middle', 'order' => 2]);

        $response = $this->getJson('/api/v1/services');

        $data = $response->json('data');
        $this->assertEquals('First', $data[0]['title']);
        $this->assertEquals('Middle', $data[1]['title']);
        $this->assertEquals('Last', $data[2]['title']);
    }

    public function test_services_response_has_correct_structure()
    {
        Service::factory()->create();

        $response = $this->getJson('/api/v1/services');

        $response->assertJsonStructure([
            'data' => [
                ['id', 'title', 'description', 'icon', 'image', 'order']
            ]
        ]);
    }

    public function test_services_returns_localized_title_based_on_header()
    {
        $service = Service::factory()->create([
            'title_en' => 'Residential',
            'title_ar' => 'سكني'
        ]);

        $responseAr = $this->getJson('/api/v1/services', ['Accept-Language' => 'ar']);
        $responseAr->assertJsonPath('data.0.title', 'سكني');

        $responseEn = $this->getJson('/api/v1/services', ['Accept-Language' => 'en']);
        $responseEn->assertJsonPath('data.0.title', 'Residential');
    }

    public function test_can_get_single_active_service_by_id()
    {
        $service = Service::factory()->create(['is_active' => true]);

        $response = $this->getJson("/api/v1/services/{$service->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $service->id);
    }

    public function test_inactive_service_returns_404()
    {
        $service = Service::factory()->create(['is_active' => false]);

        $response = $this->getJson("/api/v1/services/{$service->id}");

        $response->assertNotFound();
    }

    public function test_services_endpoint_is_publicly_accessible()
    {
        $response = $this->getJson('/api/v1/services');
        $response->assertOk();
    }
}
