<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_en' => fake()->words(3, true),
            'title_ar' => 'مشروع ' . fake()->word(),
            'category' => fake()->randomElement(['residential', 'commercial', 'hospitality', 'landscape', 'retail']),

            'description_en' => fake()->paragraph(),
            'description_ar' => 'وصف المشروع ' . fake()->sentence(),
            'cover_image' => 'cover.jpg',
            'gallery' => ['img1.jpg', 'img2.jpg', 'img3.jpg'],
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}
