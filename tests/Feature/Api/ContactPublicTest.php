<?php

namespace Tests\Feature\Api;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_contact_form_successfully()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '+971501234567',
            'message' => 'Hello world'
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'name', 'created_at']
            ]);

        $this->assertDatabaseHas('contacts', [
            'phone' => '+971501234567',
            'status' => 'new'
        ]);
    }

    public function test_contact_form_requires_name()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'phone' => '123456789',
            'message' => 'Hello world'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_contact_form_requires_message()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '123456789'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['message']);
    }

    public function test_contact_form_requires_phone()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'message' => 'Hello world'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_contact_name_max_255_characters()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => str_repeat('a', 256),
            'phone' => '123456789',
            'message' => 'Hello world'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_contact_message_minimum_10_characters()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '123456789',
            'message' => 'Hi'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['message']);
    }

    public function test_contact_optional_email_must_be_valid_format()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '123456789',
            'message' => 'Hello world',
            'email' => 'not-valid'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_contact_optional_fields_accepted()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '123456789',
            'email' => 'sara@example.com',
            'service' => 'Residential',
            'message' => 'Hello world'
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('contacts', [
            'email' => 'sara@example.com',
            'service' => 'Residential'
        ]);
    }

    public function test_contact_form_is_rate_limited_after_5_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/contacts', [
                'name' => 'Sara',
                'phone' => '123456789',
                'message' => 'Hello world'
            ])->assertCreated();
        }

        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '123456789',
            'message' => 'Hello world'
        ]);

        $response->assertStatus(429);
    }

    public function test_contact_default_status_is_new()
    {
        $response = $this->postJson('/api/v1/contacts', [
            'name' => 'Sara',
            'phone' => '123456789',
            'message' => 'Hello world'
        ]);

        $response->assertCreated();
        
        $this->assertDatabaseHas('contacts', [
            'status' => 'new'
        ]);
    }
}
