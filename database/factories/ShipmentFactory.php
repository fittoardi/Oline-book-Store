<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        return ['order_id' => Order::factory(), 'courier' => fake()->randomElement(['JNE', 'J&T', 'SiCepat']), 'service' => 'REG', 'tracking_number' => 'TRK'.fake()->numerify('########'), 'shipping_cost' => 15000, 'estimated_delivery' => '2-4 business days', 'status' => 'pending', 'shipped_at' => null, 'delivered_at' => null];
    }
}
