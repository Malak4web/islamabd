<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_unauthenticated_cannot_manage_projects()
    {
        $this->getJson('/api/admin/projects')->assertUnauthorized();
    }

    public function test_admin_can_list_all_projects_including_inactive()
    {
        Project::factory()->count(4)->create(['is_active' => true]);
        Project::factory()->count(2)->create(['is_active' => false]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/projects')
            ->assertOk()
            ->assertJsonCount(6, 'data');
    }

    public function test_admin_can_create_project()
    {
        $data = [
            'title_en' => 'Dubai Marina Penthouse',
            'title_ar' => 'بنتهاوس دبي مارينا',
            'category' => 'residential',
            'description_en' => 'Luxury living',
            'description_ar' => 'حياة فاخرة',
            'is_featured' => true,
            'is_active' => true,
            'order' => 1
        ];

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/projects', $data)
            ->assertCreated();

        $this->assertDatabaseHas('projects', ['title_en' => 'Dubai Marina Penthouse']);
    }

    public function test_create_requires_title_en_and_title_ar()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/projects', [])
            ->assertJsonValidationErrors(['title_en', 'title_ar']);
    }

    public function test_create_requires_valid_category()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/projects', ['category' => 'invalid'])
            ->assertJsonValidationErrors(['category']);
    }

    public function test_admin_can_update_project()
    {
        $project = Project::factory()->create(['category' => 'residential']);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/projects/{$project->id}", [
                'title_en' => 'Updated Title',
                'title_ar' => 'عنوان محدث',
                'category' => 'commercial'
            ])
            ->assertOk();

        $this->assertEquals('commercial', $project->fresh()->category);
    }

    public function test_admin_can_upload_cover_image()
    {
        Storage::fake('public');
        $project = Project::factory()->create();
        $file = UploadedFile::fake()->create('cover.jpg', 100);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/projects/{$project->id}/cover", [
                'file' => $file
            ])
            ->assertOk()
            ->assertJsonStructure(['data' => ['url']]);

        $this->assertNotNull($project->fresh()->cover_image);
        Storage::disk('public')->assertExists($project->fresh()->cover_image);
    }

    public function test_admin_can_upload_gallery_images()
    {
        Storage::fake('public');
        $project = Project::factory()->create(['gallery' => []]);
        $files = [
            UploadedFile::fake()->create('img1.jpg', 100),
            UploadedFile::fake()->create('img2.jpg', 100)
        ];

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/projects/{$project->id}/gallery", [
                'images' => $files
            ])
            ->assertOk();

        $this->assertCount(2, $project->fresh()->gallery);
    }

    public function test_admin_can_remove_gallery_image()
    {
        Storage::fake('public');
        $project = Project::factory()->create(['gallery' => []]);
        $file = UploadedFile::fake()->create('img.jpg', 100);
        
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/projects/{$project->id}/gallery", [
                'images' => [$file]
            ]);
        
        $mediaId = $project->getMedia('gallery')->first()->id;

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/projects/{$project->id}/gallery", [
                'image_id' => $mediaId
            ])
            ->assertOk();

        $this->assertCount(0, $project->fresh()->gallery);
    }

    public function test_admin_can_toggle_featured()
    {
        $project = Project::factory()->create(['is_featured' => false]);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/projects/{$project->id}/feature")
            ->assertOk();

        $this->assertTrue($project->fresh()->is_featured);
    }

    public function test_admin_can_toggle_active()
    {
        $project = Project::factory()->create(['is_active' => true]);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/projects/{$project->id}/toggle")
            ->assertOk();

        $this->assertFalse($project->fresh()->is_active);
    }

    public function test_admin_can_delete_project()
    {
        $project = Project::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/projects/{$project->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_admin_can_reorder_projects()
    {
        $p1 = Project::factory()->create(['order' => 1]);
        $p2 = Project::factory()->create(['order' => 2]);
        $p3 = Project::factory()->create(['order' => 3]);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson('/api/admin/projects/reorder', [
                'order' => [$p3->id, $p1->id, $p2->id]
            ])
            ->assertOk();

        $this->assertEquals(1, $p3->fresh()->order);
        $this->assertEquals(2, $p1->fresh()->order);
        $this->assertEquals(3, $p2->fresh()->order);
    }
}
