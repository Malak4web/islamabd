<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'title_en' => fake()->words(3, true),
            'title_ar' => 'صفحة ' . fake()->word(),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
        ];
    }
}
