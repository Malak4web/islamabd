<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create(['role' => 'admin']);
        $this->dataEntry = Admin::factory()->create(['role' => 'data_entry']);
        $this->withHeaders([
            'Origin' => 'http://localhost',
            'Accept' => 'application/json',
        ]);
    }

    public function test_unauthenticated_cannot_access_admins()
    {
        $this->getJson('/api/admin/admins')->assertStatus(401);
    }

    public function test_data_entry_cannot_access_admins_management()
    {
        $this->actingAs($this->dataEntry, 'web')
            ->getJson('/api/admin/admins')
            ->assertStatus(403);
    }

    public function test_data_entry_cannot_update_site_settings()
    {
        $this->actingAs($this->dataEntry, 'web')
            ->putJson('/api/admin/settings/site_name', ['value' => 'New Name'])
            ->assertStatus(403);
    }

    public function test_data_entry_cannot_manage_code_injections()
    {
        $this->actingAs($this->dataEntry, 'web')
            ->getJson('/api/admin/code-injections')
            ->assertStatus(403);
    }

    public function test_data_entry_can_manage_content_projects_and_services()
    {
        // Can access projects
        $this->actingAs($this->dataEntry, 'web')
            ->getJson('/api/admin/projects')
            ->assertOk();

        // Can access services
        $this->actingAs($this->dataEntry, 'web')
            ->getJson('/api/admin/services')
            ->assertOk();

        // Can create a service
        $response = $this->actingAs($this->dataEntry, 'web')
            ->postJson('/api/admin/services', [
                'title_en' => 'Interior Styling',
                'title_ar' => 'تنسيق داخلي',
                'description_en' => 'Curated spaces',
                'description_ar' => 'مساحات مميزة',
                'is_active' => true
            ]);

        $response->assertCreated();
    }

    public function test_admin_can_list_team_members()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/admins');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_create_new_data_entry_member()
    {
        $payload = [
            'name' => 'Sara Content Manager',
            'email' => 'sara@example.com',
            'role' => 'data_entry',
            'password' => 'secret1234'
        ];

        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/admins', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Sara Content Manager')
            ->assertJsonPath('data.role', 'data_entry');

        $this->assertDatabaseHas('admins', [
            'email' => 'sara@example.com',
            'role' => 'data_entry'
        ]);
    }

    public function test_admin_can_update_member_role()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->putJson("/api/admin/admins/{$this->dataEntry->id}", [
                'name' => 'Promoted Data Entry',
                'email' => $this->dataEntry->email,
                'role' => 'admin'
            ]);

        $response->assertOk()
            ->assertJsonPath('data.role', 'admin');

        $this->assertDatabaseHas('admins', [
            'id' => $this->dataEntry->id,
            'role' => 'admin'
        ]);
    }

    public function test_admin_cannot_delete_own_account()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->deleteJson("/api/admin/admins/{$this->admin->id}");

        $response->assertStatus(422);
        $this->assertDatabaseHas('admins', ['id' => $this->admin->id]);
    }

    public function test_admin_can_delete_other_member()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->deleteJson("/api/admin/admins/{$this->dataEntry->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('admins', ['id' => $this->dataEntry->id]);
    }
}
