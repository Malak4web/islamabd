<?php

namespace Tests\Unit\Models;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_has_correct_fillable_fields()
    {
        $project = new Project();
        $this->assertEquals([
            'title_en', 'title_ar', 'category', 'description_en', 'description_ar',
            'cover_image', 'gallery', 'is_featured', 'is_active', 'order'
        ], $project->getFillable());
    }

    public function test_gallery_cast_to_array()
    {
        $gallery = ['image1.jpg', 'image2.jpg'];
        $project = Project::factory()->create(['gallery' => $gallery]);
        $this->assertIsArray($project->gallery);
        $this->assertCount(2, $project->gallery);
    }

    public function test_scope_featured_returns_featured_only()
    {
        Project::factory()->count(2)->create(['is_featured' => true]);
        Project::factory()->count(3)->create(['is_featured' => false]);
        $this->assertEquals(2, Project::featured()->count());
    }

    public function test_scope_by_category_filters()
    {
        Project::factory()->count(3)->create(['category' => 'residential']);
        Project::factory()->count(2)->create(['category' => 'commercial']);
        $this->assertEquals(3, Project::byCategory('residential')->count());
    }

    public function test_scope_active_and_ordered()
    {
        Project::factory()->create(['is_active' => true, 'order' => 2]);
        Project::factory()->create(['is_active' => true, 'order' => 1]);
        Project::factory()->create(['is_active' => false, 'order' => 3]);
        
        $this->assertEquals(1, Project::active()->ordered()->first()->order);
        $this->assertEquals(2, Project::active()->count());
    }
}
