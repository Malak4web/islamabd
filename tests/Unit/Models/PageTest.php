<?php

namespace Tests\Unit\Models;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_has_correct_fillable_fields()
    {
        $page = new Page();
        $this->assertEquals([
            'slug', 'title_en', 'title_ar', 'meta_title', 'meta_description', 'og_image'
        ], $page->getFillable());
    }

    public function test_page_slug_is_unique()
    {
        Page::create(['slug' => 'home', 'title_en' => 'Home', 'title_ar' => 'الرئيسية']);
        
        $this->expectException(QueryException::class);
        Page::create(['slug' => 'home', 'title_en' => 'Duplicate', 'title_ar' => 'مكرر']);
    }

    public function test_page_has_many_sections()
    {
        $page = Page::factory()->create();
        Section::factory()->count(3)->create(['page_id' => $page->id]);

        $this->assertEquals(3, $page->sections->count());
        $this->assertInstanceOf(Section::class, $page->sections->first());
    }

    public function test_page_sections_ordered_by_order_column()
    {
        $page = Page::factory()->create();
        Section::factory()->create(['page_id' => $page->id, 'order' => 3]);
        Section::factory()->create(['page_id' => $page->id, 'order' => 1]);
        Section::factory()->create(['page_id' => $page->id, 'order' => 2]);

        $this->assertEquals(1, $page->sections->first()->order);
    }
}
