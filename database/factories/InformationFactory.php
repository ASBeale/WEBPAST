<?php

namespace Database\Factories;

use App\Models\JenisKegiatan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Information>
 */
class InformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => Str::slug(fake()->sentence()),
            'body' => fake()->paragraph(10, true),
            // 'image' => $this->faker->imageUrl(),
            'image' => fake()->imageUrl()
        ];
    }
}
