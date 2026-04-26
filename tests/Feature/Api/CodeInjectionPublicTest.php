<?php

namespace Tests\Feature\Api;

use App\Models\CodeInjection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeInjectionPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_active_injections_grouped_by_location()
    {
        CodeInjection::factory()->create([
            'is_active' => true,
            'location' => 'head',
            'pages' => null
        ]);

        CodeInjection::factory()->create([
            'is_active' => true,
            'location' => 'body_end',
            'pages' => null
        ]);

        CodeInjection::factory()->create([
            'is_active' => false,
            'location' => 'head',
            'pages' => null
        ]);

        $this->getJson('/api/v1/code-injections?page=home')
            ->assertOk()
            ->assertJsonCount(1, 'data.head')
            ->assertJsonCount(1, 'data.body_end')
            ->assertJsonCount(0, 'data.body_start');
    }

    public function test_all_pages_injection_appears_on_every_page()
    {
        CodeInjection::factory()->create([
            'is_active' => true,
            'location' => 'head',
            'pages' => null
        ]);

        $this->getJson('/api/v1/code-injections?page=home')
            ->assertJsonCount(1, 'data.head');

        $this->getJson('/api/v1/code-injections?page=about')
            ->assertJsonCount(1, 'data.head');

        $this->getJson('/api/v1/code-injections?page=contact')
            ->assertJsonCount(1, 'data.head');
    }

    public function test_specific_page_injection_only_on_that_page()
    {
        CodeInjection::factory()->create([
            'is_active' => true,
            'location' => 'head',
            'pages' => ['home']
        ]);

        $this->getJson('/api/v1/code-injections?page=home')
            ->assertJsonCount(1, 'data.head');

        $this->getJson('/api/v1/code-injections?page=about')
            ->assertJsonCount(0, 'data.head');
    }

    public function test_multiple_pages_injection_appears_on_each()
    {
        CodeInjection::factory()->create([
            'is_active' => true,
            'location' => 'body_end',
            'pages' => ['home', 'about']
        ]);

        $this->getJson('/api/v1/code-injections?page=home')
            ->assertJsonCount(1, 'data.body_end');

        $this->getJson('/api/v1/code-injections?page=about')
            ->assertJsonCount(1, 'data.body_end');

        $this->getJson('/api/v1/code-injections?page=contact')
            ->assertJsonCount(0, 'data.body_end');
    }

    public function test_inactive_injections_not_returned()
    {
        CodeInjection::factory()->create([
            'is_active' => false,
            'location' => 'head',
            'pages' => null
        ]);

        $this->getJson('/api/v1/code-injections?page=home')
            ->assertJsonCount(0, 'data.head')
            ->assertJsonCount(0, 'data.body_start')
            ->assertJsonCount(0, 'data.body_end');
    }

    public function test_response_structure_has_head_body_start_body_end()
    {
        $this->getJson('/api/v1/code-injections?page=home')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'head',
                    'body_start',
                    'body_end'
                ]
            ]);
    }

    public function test_each_injection_has_id_name_code_fields()
    {
        CodeInjection::factory()->create([
            'name' => 'GTM',
            'code' => '<script>gtm()</script>',
            'is_active' => true,
            'location' => 'head',
            'pages' => null
        ]);

        $this->getJson('/api/v1/code-injections?page=home')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'head' => [
                        '*' => ['id', 'name', 'code']
                    ]
                ]
            ]);
    }

    public function test_endpoint_publicly_accessible_no_auth()
    {
        $this->getJson('/api/v1/code-injections?page=home')
            ->assertOk();
    }
}
