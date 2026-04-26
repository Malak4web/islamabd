<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\CodeInjection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeInjectionAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_unauthenticated_cannot_manage_injections()
    {
        $this->getJson('/api/admin/code-injections')->assertUnauthorized();
    }

    public function test_admin_can_list_all_code_injections()
    {
        CodeInjection::factory()->count(4)->create();

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/code-injections')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'code', 'location', 'is_active', 'pages']
                ]
            ]);
    }

    public function test_admin_can_create_gtm_injection()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/code-injections', [
                'name' => 'Google Tag Manager',
                'code' => '<!-- GTM --><script>gtm()</script>',
                'location' => 'head',
                'is_active' => true,
                'pages' => null
            ])
            ->assertCreated();

        $this->assertDatabaseHas('code_injections', [
            'name' => 'Google Tag Manager',
            'location' => 'head'
        ]);
    }

    public function test_create_requires_name_and_code()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/code-injections', [
                'location' => 'head'
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'code']);
    }

    public function test_create_requires_valid_location()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/code-injections', [
                'name' => 'Test',
                'code' => '...',
                'location' => 'invalid'
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['location']);
    }

    public function test_create_with_specific_pages()
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/admin/code-injections', [
                'name' => 'Test',
                'code' => '...',
                'location' => 'head',
                'pages' => ['home', 'about']
            ])
            ->assertCreated();

        $inj = CodeInjection::first();
        $this->assertCount(2, $inj->pages);
        $this->assertTrue(in_array('home', $inj->pages));
    }

    public function test_admin_can_update_injection()
    {
        $inj = CodeInjection::factory()->create([
            'name' => 'Old Name',
            'code' => 'Old Code'
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/admin/code-injections/{$inj->id}", [
                'name' => 'New Name',
                'code' => 'New Code',
                'location' => 'head',
                'is_active' => true,
                'pages' => null
            ])
            ->assertOk();

        $this->assertDatabaseHas('code_injections', [
            'id' => $inj->id,
            'name' => 'New Name',
            'code' => 'New Code'
        ]);
    }

    public function test_admin_can_toggle_injection_active()
    {
        $inj = CodeInjection::factory()->create([
            'is_active' => true
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/code-injections/{$inj->id}/toggle")
            ->assertOk();

        $this->assertFalse($inj->fresh()->is_active);
    }

    public function test_admin_can_delete_injection()
    {
        $inj = CodeInjection::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/code-injections/{$inj->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('code_injections', ['id' => $inj->id]);
    }

    public function test_admin_can_get_single_injection_with_full_code()
    {
        $fullCode = '<script>let x = 1; let y = 2; console.log(x + y);</script>';
        $inj = CodeInjection::factory()->create([
            'code' => $fullCode
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/code-injections/{$inj->id}")
            ->assertOk()
            ->assertJsonPath('data.code', $fullCode);
    }
}
