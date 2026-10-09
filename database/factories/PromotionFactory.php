<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => fake()->sentence(3), 'type' => fake()->randomElement(['percentage', 'fixed']), 'value' => fake()->numberBetween(5, 25), 'minimum_purchase' => 200000, 'starts_at' => now()->subDay(), 'expires_at' => now()->addMonth(), 'is_active' => true];
    }
}
