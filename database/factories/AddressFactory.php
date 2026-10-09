<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return ['user_id' => User::factory(), 'label' => fake()->randomElement(['Rumah', 'Kantor', 'Kos']), 'recipient_name' => fake()->name(), 'phone' => fake()->numerify('08##########'), 'province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan', 'district' => 'Kebayoran Baru', 'postal_code' => fake()->numerify('#####'), 'full_address' => fake()->streetAddress(), 'is_default' => false];
    }
}
