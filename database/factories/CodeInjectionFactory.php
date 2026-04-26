<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CodeInjection>
 */
class CodeInjectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Script',
            'code' => '<script>console.log("' . fake()->word() . '");</script>',
            'location' => fake()->randomElement(['head', 'body_start', 'body_end']),
            'is_active' => true,
            'pages' => null, // Default to all pages
        ];
    }
}
