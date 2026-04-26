<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
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
            'title_ar' => 'خدمة ' . fake()->word(),
            'description_en' => fake()->paragraph(),
            'description_ar' => 'وصف الخدمة ' . fake()->sentence(),
            'icon' => 'heroicons-o-star',
            'order' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
