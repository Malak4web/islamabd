<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaFile>
 */
class MediaFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filename' => fake()->word() . '.jpg',
            'path' => 'media/' . fake()->uuid() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(1000, 5000000),
        ];
    }
}
