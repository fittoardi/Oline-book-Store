<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    public function definition(): array
    {
        return ['code' => 'READ'.fake()->unique()->numerify('####'), 'name' => 'Reader discount', 'type' => 'percentage', 'value' => fake()->numberBetween(5, 25), 'minimum_purchase' => 150000, 'maximum_discount' => 75000, 'usage_limit' => 100, 'usage_limit_per_user' => 1, 'used_count' => 0, 'starts_at' => now()->subDay(), 'expires_at' => now()->addMonth(), 'is_active' => true];
    }
}
