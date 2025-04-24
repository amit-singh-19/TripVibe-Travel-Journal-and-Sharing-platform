<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JournalFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', '+1 year');
        $endDate = fake()->dateTimeBetween($startDate, '+1 month');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->city() . ', ' . fake()->country(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'is_public' => fake()->boolean(80), // 80% chance of being public
        ];
    }
} 