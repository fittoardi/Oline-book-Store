<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $price = fake()->numberBetween(59000, 249000);
        $quantity = fake()->numberBetween(1, 3);

        return ['order_id' => Order::factory(), 'product_variant_id' => ProductVariant::factory(), 'product_name' => fake()->words(3, true), 'variant_name' => fake()->randomElement(['Paperback', 'Hardcover']), 'sku' => 'KB-'.fake()->numerify('######'), 'quantity' => $quantity, 'unit_price' => $price, 'subtotal' => $price * $quantity];
    }
}
