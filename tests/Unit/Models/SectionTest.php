<?php

namespace Tests\Unit\Models;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_section_has_correct_fillable_fields()
    {
        $section = new Section();
        $this->assertEquals(['page_id', 'key', 'content', 'order', 'is_active'], $section->getFillable());
    }

    public function test_section_content_cast_to_array()
    {
        $content = ['title' => 'Hello', 'body' => 'World'];
        $section = Section::factory()->create(['content' => $content]);
        
        $this->assertIsArray($section->content);
        $this->assertEquals('Hello', $section->content['title']);
    }

    public function test_section_belongs_to_page()
    {
        $page = Page::factory()->create();
        $section = Section::factory()->create(['page_id' => $page->id]);
        
        $this->assertInstanceOf(Page::class, $section->page);
        $this->assertEquals($page->id, $section->page->id);
    }

    public function test_scope_active_filters_correctly()
    {
        Section::factory()->count(2)->create(['is_active' => true]);
        Section::factory()->create(['is_active' => false]);
        
        $this->assertEquals(2, Section::active()->count());
    }

    public function test_scope_ordered_sorts_ascending()
    {
        Section::factory()->create(['order' => 3]);
        Section::factory()->create(['order' => 1]);
        Section::factory()->create(['order' => 2]);
        
        $this->assertEquals(1, Section::ordered()->first()->order);
    }
}
