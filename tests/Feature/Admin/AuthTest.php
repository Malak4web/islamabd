<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders([
            'Origin' => 'http://localhost',
            'Accept' => 'application/json',
        ]);
        $this->admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
    }


    public function test_admin_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
                'message'
            ]);

        $this->assertAuthenticatedAs($this->admin, 'web');
    }

    public function test_admin_cannot_login_with_wrong_password()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }

    public function test_admin_cannot_login_with_nonexistent_email()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'fake@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function test_login_requires_email_field()
    {
        $response = $this->postJson('/api/admin/login', [
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_requires_password_field()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@test.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_email_must_be_valid_format()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'not-an-email',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_admin_can_get_user_profile()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/admin/user');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email']
            ]);
    }

    public function test_unauthenticated_request_returns_401()
    {
        $response = $this->getJson('/api/admin/user');

        $response->assertStatus(401);
    }

    public function test_admin_can_logout()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->postJson('/api/admin/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Logged out successfully.']);

        $this->assertGuest('web');
    }

    public function test_rate_limiting_blocks_after_many_failed_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/admin/login', [
                'email' => 'admin@test.com',
                'password' => 'wrong',
            ]);
        }

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(429);
    }
}
