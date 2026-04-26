<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceAdminTest extends TestCase
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


    public function test_unauthenticated_cannot_access_admin_services()
    {
        $this->getJson('/api/admin/services')->assertStatus(401);
    }

    public function test_admin_can_list_all_services_including_inactive()
    {
        Service::factory()->count(3)->create(['is_active' => true]);
        Service::factory()->count(2)->create(['is_active' => false]);

        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/services');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_create_service()
    {
        $data = [
            'title_en' => 'Residential Interior Design',
            'title_ar' => 'تصميم داخلي سكني',
            'description_en' => 'We transform living spaces.',
            'description_ar' => 'نحول المساحات المعيشية.',
            'order' => 1,
            'is_active' => true
        ];

        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/services', $data);

        $response->assertCreated();
        $this->assertDatabaseHas('services', ['title_en' => 'Residential Interior Design']);
    }

    public function test_create_requires_title_en_and_title_ar()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/services', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title_en', 'title_ar']);
    }

    public function test_admin_can_update_service()
    {
        $service = Service::factory()->create(['title_en' => 'Old Title']);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson("/api/admin/services/{$service->id}", [
                'title_en' => 'Updated Title',
                'title_ar' => 'محدث',
                'description_en' => 'Desc',
                'description_ar' => 'وصف'
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('services', ['title_en' => 'Updated Title']);
    }

    public function test_admin_can_toggle_service_active_status()
    {
        $service = Service::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin, 'web')
            ->patchJson("/api/admin/services/{$service->id}/toggle");

        $response->assertOk();
        $this->assertFalse($service->fresh()->is_active);
    }

    public function test_admin_can_delete_service()
    {
        $service = Service::factory()->create();

        $response = $this->actingAs($this->admin, 'web')
            ->deleteJson("/api/admin/services/{$service->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_admin_can_reorder_services()
    {
        $s1 = Service::factory()->create(['order' => 1]);
        $s2 = Service::factory()->create(['order' => 2]);
        $s3 = Service::factory()->create(['order' => 3]);

        $response = $this->actingAs($this->admin, 'web')
            ->patchJson('/api/admin/services/reorder', [
                'order' => [$s3->id, $s1->id, $s2->id]
            ]);

        $response->assertOk();
        $this->assertEquals(1, $s3->fresh()->order);
        $this->assertEquals(2, $s1->fresh()->order);
        $this->assertEquals(3, $s2->fresh()->order);
    }

    public function test_admin_can_upload_service_image()
    {
        Storage::fake('public');
        $service = Service::factory()->create();
        $file = UploadedFile::fake()->create('service.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin, 'web')
            ->postJson("/api/admin/services/{$service->id}/image", [
                'file' => $file
            ]);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['url']]);
        
        $this->assertNotNull($service->fresh()->image);
        Storage::disk('public')->assertExists($service->fresh()->image);
    }

    public function test_admin_can_upload_service_icon()
    {
        Storage::fake('public');
        $service = Service::factory()->create();
        $file = UploadedFile::fake()->create('icon.svg', 10, 'image/svg+xml');

        $response = $this->actingAs($this->admin, 'web')
            ->postJson("/api/admin/services/{$service->id}/icon", [
                'file' => $file
            ]);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['url']]);
        
        $this->assertNotNull($service->fresh()->icon);
        Storage::disk('public')->assertExists($service->fresh()->icon);
    }
}

