<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageAdminTest extends TestCase
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

    public function test_unauthenticated_cannot_access_admin_pages()
    {
        $this->getJson('/api/admin/pages')->assertStatus(401);
    }

    public function test_admin_can_list_all_pages()
    {
        Page::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/pages');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_update_page_seo()
    {
        $page = Page::factory()->create(['slug' => 'home']);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson("/api/admin/pages/{$page->id}", [
                'meta_title' => 'New Meta',
                'meta_description' => 'Description',
                'og_image' => '/img.jpg',
                'title_en' => 'Home Updated',
                'title_ar' => 'الرئيسية محدث'
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'meta_title' => 'New Meta',
            'title_en' => 'Home Updated'
        ]);
    }

    public function test_admin_can_get_sections_for_page()
    {
        $page = Page::factory()->create();
        Section::factory()->count(4)->create(['page_id' => $page->id]);

        $response = $this->actingAs($this->admin, 'web')
            ->getJson("/api/admin/sections/{$page->id}");

        $response->assertOk()
            ->assertJsonCount(4, 'data');
    }

    public function test_admin_can_update_section_content()
    {
        $section = Section::factory()->create(['content' => ['title' => 'Old']]);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson("/api/admin/sections/{$section->id}", [
                'content' => ['title' => 'New', 'subtitle' => 'Sub']
            ]);

        $response->assertOk();
        $this->assertEquals('New', $section->fresh()->content['title']);
    }

    public function test_admin_can_toggle_section_active_status()
    {
        $section = Section::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin, 'web')
            ->patchJson("/api/admin/sections/{$section->id}/toggle");

        $response->assertOk();
        $this->assertFalse($section->fresh()->is_active);
    }

    public function test_admin_can_reorder_sections()
    {
        $page = Page::factory()->create();
        $s1 = Section::factory()->create(['page_id' => $page->id, 'order' => 1]);
        $s2 = Section::factory()->create(['page_id' => $page->id, 'order' => 2]);
        $s3 = Section::factory()->create(['page_id' => $page->id, 'order' => 3]);

        $response = $this->actingAs($this->admin, 'web')
            ->patchJson("/api/admin/sections/reorder", [
                'order' => [$s3->id, $s1->id, $s2->id]
            ]);

        $response->assertOk();
        $this->assertEquals(1, $s3->fresh()->order);
        $this->assertEquals(2, $s1->fresh()->order);
        $this->assertEquals(3, $s2->fresh()->order);
    }
}
