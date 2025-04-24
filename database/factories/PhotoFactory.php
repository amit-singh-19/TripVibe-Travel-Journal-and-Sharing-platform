<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path' => 'sample-photos/placeholder.jpg',
            'caption' => fake()->sentence(3),
        ];
    }
} 