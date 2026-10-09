<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistItemFactory extends Factory
{
    public function definition(): array
    {
        return ['wishlist_id' => Wishlist::factory(), 'product_id' => Product::factory()];
    }
}
