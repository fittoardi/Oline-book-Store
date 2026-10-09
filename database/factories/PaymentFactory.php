<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return ['order_id' => Order::factory(), 'provider' => 'tripay', 'reference' => 'REF-'.fake()->unique()->numerify('########'), 'merchant_ref' => 'KB-PAY-'.fake()->unique()->numerify('######'), 'method' => fake()->randomElement(['Bank transfer', 'QRIS', 'E-wallet']), 'status' => 'unpaid', 'amount' => fake()->numberBetween(100000, 900000), 'checkout_url' => null, 'paid_at' => null, 'raw_response' => ['demo' => true]];
    }
}
