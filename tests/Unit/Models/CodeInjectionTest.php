<?php

namespace Tests\Unit\Models;

use App\Models\CodeInjection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeInjectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_correct_fillable_fields()
    {
        $injection = new CodeInjection();
        $this->assertEquals(['name', 'code', 'location', 'is_active', 'pages'], $injection->getFillable());
    }

    public function test_pages_cast_to_array()
    {
        $pages = ['home', 'about'];
        $injection = CodeInjection::factory()->create(['pages' => $pages]);
        $this->assertIsArray($injection->pages);
        $this->assertCount(2, $injection->pages);
    }

    public function test_null_pages_means_all_pages()
    {
        $injection = CodeInjection::factory()->create(['pages' => null]);
        $this->assertNull($injection->pages);
    }

    public function test_scope_active_returns_active_only()
    {
        CodeInjection::factory()->count(3)->create(['is_active' => true]);
        CodeInjection::factory()->create(['is_active' => false]);
        $this->assertEquals(3, CodeInjection::active()->count());
    }

    public function test_scope_for_page_returns_all_pages_plus_specific()
    {
        CodeInjection::factory()->create(['pages' => null, 'is_active' => true]); // Global
        CodeInjection::factory()->create(['pages' => ['home'], 'is_active' => true]); // Home only
        CodeInjection::factory()->create(['pages' => ['about'], 'is_active' => true]); // About only
        CodeInjection::factory()->create(['pages' => ['home'], 'is_active' => false]); // Inactive
        
        $this->assertEquals(2, CodeInjection::active()->forPage('home')->count());
        $this->assertEquals(2, CodeInjection::active()->forPage('about')->count());
    }
}
