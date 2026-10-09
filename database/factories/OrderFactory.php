<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(150000, 800000);
        $shipping = 15000;

        return ['user_id' => User::factory(), 'address_id' => Address::factory(), 'order_number' => 'KB-'.fake()->unique()->numerify('######'), 'status' => 'pending', 'payment_status' => 'unpaid', 'shipping_status' => 'pending', 'subtotal' => $subtotal, 'discount_amount' => 0, 'shipping_cost' => $shipping, 'total_amount' => $subtotal + $shipping, 'shipping_courier' => 'JNE', 'tracking_number' => null, 'notes' => null];
    }
}
