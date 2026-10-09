<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        $format = fake()->randomElement(['paperback', 'hardcover', 'gift_edition']);

        return [
            'product_id' => Product::factory(),
            'sku' => 'KB-'.fake()->unique()->bothify('???###'),
            'format' => $format,
            'stock' => fake()->numberBetween(0, 50),
            'reserved_stock' => 0,
            'price' => null,
            'is_active' => true,
        ];
    }
}
