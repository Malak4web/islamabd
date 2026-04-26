<?php

namespace Tests\Unit\Models;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_has_correct_fillable_fields()
    {
        $contact = new Contact();
        $this->assertEquals(['name', 'phone', 'email', 'service', 'message', 'status'], $contact->getFillable());
    }

    public function test_default_status_is_new()
    {
        $contact = Contact::create([
            'name' => 'John', 'phone' => '123', 'message' => 'Hello'
        ]);
        $this->assertEquals('new', $contact->status);
    }

    public function test_scope_unread_returns_new_status_only()
    {
        Contact::factory()->count(2)->create(['status' => 'new']);
        Contact::factory()->count(2)->create(['status' => 'read']);
        $this->assertEquals(2, Contact::unread()->count());
    }

    public function test_mark_as_read_method_updates_status()
    {
        $contact = Contact::factory()->create(['status' => 'new']);
        $contact->markAsRead();
        $this->assertEquals('read', $contact->fresh()->status);
    }
}
