<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_list_of_active_projects()
    {
        Project::factory()->count(5)->create(['is_active' => true]);
        Project::factory()->count(2)->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_inactive_projects_excluded()
    {
        Project::factory()->create(['title_en' => 'Inactive Project', 'is_active' => false]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertJsonMissing(['title' => 'Inactive Project']);
    }

    public function test_can_filter_projects_by_category()
    {
        Project::factory()->count(3)->create(['category' => 'residential', 'is_active' => true]);
        Project::factory()->count(2)->create(['category' => 'commercial', 'is_active' => true]);

        $response = $this->getJson('/api/v1/projects?category=residential');

        $response->assertJsonCount(3, 'data');
    }

    public function test_can_filter_featured_projects()
    {
        Project::factory()->count(2)->create(['is_featured' => true, 'is_active' => true]);
        Project::factory()->count(3)->create(['is_featured' => false, 'is_active' => true]);

        $response = $this->getJson('/api/v1/projects?featured=1');

        $response->assertJsonCount(2, 'data');
    }

    public function test_projects_returned_in_order()
    {
        Project::factory()->create(['title_en' => 'Second', 'order' => 2, 'is_active' => true]);
        Project::factory()->create(['title_en' => 'First', 'order' => 1, 'is_active' => true]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertJsonPath('data.0.title', 'First');
    }

    public function test_projects_response_structure()
    {
        Project::factory()->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'category', 'cover_image', 'is_featured', 'order']
            ]
        ]);
    }

    public function test_can_get_single_project_with_gallery()
    {
        $project = Project::factory()->create([
            'is_active' => true,
            'gallery' => ['img1.jpg', 'img2.jpg', 'img3.jpg']
        ]);

        $response = $this->getJson("/api/v1/projects/{$project->id}");

        $response->assertOk()
            ->assertJsonCount(3, 'data.gallery')
            ->assertJsonStructure([
                'data' => ['id', 'title', 'category', 'description', 'cover_image', 'gallery']
            ]);
    }

    public function test_inactive_project_returns_404()
    {
        $project = Project::factory()->create(['is_active' => false]);

        $response = $this->getJson("/api/v1/projects/{$project->id}");

        $response->assertNotFound();
    }

    public function test_projects_localized_by_accept_language()
    {
        Project::factory()->create([
            'title_en' => 'Palm Villa',
            'title_ar' => 'فيلا النخيل',
            'is_active' => true
        ]);

        $response = $this->getJson('/api/v1/projects', ['Accept-Language' => 'ar']);

        $response->assertJsonPath('data.0.title', 'فيلا النخيل');
    }

    public function test_projects_support_pagination()
    {
        Project::factory()->count(15)->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/projects?per_page=9');

        $response->assertJsonCount(9, 'data')
            ->assertJsonStructure([
                'meta' => ['total', 'per_page', 'current_page', 'last_page']
            ])
            ->assertJsonPath('meta.total', 15);
    }
}
