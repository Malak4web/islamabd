<?php

namespace Tests\Feature\Api;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagePublicTest extends TestCase
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

    public function test_can_get_home_page_with_sections()
    {
        $page = Page::factory()->create(['slug' => 'home']);
        Section::factory()->count(3)->create(['page_id' => $page->id, 'is_active' => true]);

        $response = $this->getJson('/api/v1/pages/home');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id', 'slug', 'title_en', 'title_ar', 'meta_title', 'meta_description', 'og_image', 'sections'
                ]
            ])
            ->assertJsonCount(3, 'data.sections');
    }

    public function test_inactive_sections_excluded()
    {
        $page = Page::factory()->create(['slug' => 'home']);
        Section::factory()->create(['page_id' => $page->id, 'is_active' => true]);
        Section::factory()->create(['page_id' => $page->id, 'is_active' => false]);

        $response = $this->getJson('/api/v1/pages/home');

        $response->assertJsonCount(1, 'data.sections');
    }

    public function test_sections_returned_in_order()
    {
        $page = Page::factory()->create(['slug' => 'home']);
        Section::factory()->create(['page_id' => $page->id, 'key' => 'last', 'order' => 3]);
        Section::factory()->create(['page_id' => $page->id, 'key' => 'first', 'order' => 1]);
        Section::factory()->create(['page_id' => $page->id, 'key' => 'middle', 'order' => 2]);

        $response = $this->getJson('/api/v1/pages/home');

        $sections = $response->json('data.sections');
        $this->assertEquals('first', $sections[0]['key']);
        $this->assertEquals('middle', $sections[1]['key']);
        $this->assertEquals('last', $sections[2]['key']);
    }

    public function test_page_returns_404_for_invalid_slug()
    {
        $response = $this->getJson('/api/v1/pages/nonexistent');
        $response->assertNotFound();
    }

    public function test_page_returns_arabic_title_on_ar_header()
    {
        $page = Page::factory()->create([
            'slug' => 'home',
            'title_en' => 'Home',
            'title_ar' => 'الرئيسية'
        ]);

        $response = $this->getJson('/api/v1/pages/home', [
            'Accept-Language' => 'ar'
        ]);

        $response->assertJsonPath('data.title', 'الرئيسية');
    }

    public function test_page_endpoint_is_publicly_accessible()
    {
        $page = Page::factory()->create(['slug' => 'home']);
        $response = $this->getJson('/api/v1/pages/home');
        $response->assertOk();
    }
}
