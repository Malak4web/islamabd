<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_unauthenticated_cannot_access_admin_contacts()
    {
        $this->getJson('/api/admin/contacts')->assertUnauthorized();
    }

    public function test_admin_can_list_all_contacts_paginated()
    {
        Contact::factory()->count(25)->create();

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/contacts')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 15)
            ->assertJsonPath('meta.total', 25)
            ->assertJsonCount(15, 'data');
    }

    public function test_admin_can_filter_by_status_new()
    {
        Contact::factory()->count(4)->create(['status' => 'new']);
        Contact::factory()->count(3)->create(['status' => 'read']);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/contacts?status=new')
            ->assertOk()
            ->assertJsonCount(4, 'data');
    }

    public function test_admin_can_filter_by_status_read()
    {
        Contact::factory()->count(4)->create(['status' => 'new']);
        Contact::factory()->count(3)->create(['status' => 'read']);

        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/contacts?status=read')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_view_single_contact()
    {
        $contact = Contact::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/contacts/{$contact->id}")
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'phone', 'email', 'service', 'message', 'status', 'created_at']
            ]);
    }

    public function test_admin_can_mark_contact_as_read()
    {
        $contact = Contact::factory()->create(['status' => 'new']);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/contacts/{$contact->id}/read")
            ->assertOk();

        $this->assertEquals('read', $contact->fresh()->status);
    }

    public function test_admin_can_mark_contact_as_replied()
    {
        $contact = Contact::factory()->create(['status' => 'read']);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/contacts/{$contact->id}/replied")
            ->assertOk();

        $this->assertEquals('replied', $contact->fresh()->status);
    }

    public function test_marking_one_does_not_affect_others()
    {
        $c1 = Contact::factory()->create(['status' => 'new']);
        $c2 = Contact::factory()->create(['status' => 'new']);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/contacts/{$c1->id}/read")
            ->assertOk();

        $this->assertEquals('new', $c2->fresh()->status);
    }

    public function test_admin_can_delete_contact()
    {
        $contact = Contact::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/admin/contacts/{$contact->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_admin_can_bulk_delete_contacts()
    {
        $c1 = Contact::factory()->create();
        $c2 = Contact::factory()->create();
        $c3 = Contact::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson('/api/admin/contacts/bulk', [
                'ids' => [$c1->id, $c2->id]
            ])
            ->assertOk();

        $this->assertDatabaseMissing('contacts', ['id' => $c1->id]);
        $this->assertDatabaseMissing('contacts', ['id' => $c2->id]);
        $this->assertDatabaseHas('contacts', ['id' => $c3->id]);
    }

    public function test_dashboard_shows_new_contacts_count()
    {
        Contact::factory()->count(5)->create(['status' => 'new']);
        Contact::factory()->count(3)->create(['status' => 'read']);

        // Assuming you have a dashboard endpoint that includes this stat
        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/dashboard/stats')
            ->assertOk()
            ->assertJsonPath('data.new_contacts_count', 5);
    }
}
