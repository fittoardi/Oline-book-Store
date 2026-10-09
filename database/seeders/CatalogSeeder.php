<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentWebhook;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(20)->create();
        User::updateOrCreate(
            ['email' => 'admin@kembangbooks.test'],
            ['name' => 'Kembang Books Admin', 'phone' => '081200000000', 'role' => 'admin', 'password' => Hash::make('password')],
        );

        $categories = Category::factory()->count(20)->create();
        $products = collect(range(1, 20))->map(function (int $index) use ($categories): Product {
            return Product::factory()->create(['category_id' => $categories->random()->id]);
        });

        $variants = $products->values()->map(function (Product $product, int $index): ProductVariant {
            return ProductVariant::factory()->for($product)->create([
                'format' => ['paperback', 'hardcover', 'gift_edition'][$index % 3],
                'stock' => $index % 5 === 0 ? 0 : ($index % 3 === 0 ? 7 : 30),
            ]);
        });

        $products->each(function (Product $product): void {
            ProductImage::factory()->for($product)->create(['is_primary' => true, 'sort_order' => 0]);
        });

        $addresses = $users->map(function (User $user): Address {
            return Address::factory()->for($user)->create(['is_default' => true]);
        });

        $carts = $users->values()->map(function (User $user, int $index) use ($variants): Cart {
            $cart = Cart::factory()->for($user)->create(['status' => 'active']);
            $variant = $variants[$index];
            CartItem::factory()->for($cart)->for($variant, 'productVariant')->create(['quantity' => 1, 'unit_price' => $variant->price ?? 149000]);

            return $cart;
        });

        $wishlists = $users->values()->map(function (User $user, int $index) use ($products): Wishlist {
            $wishlist = Wishlist::factory()->for($user)->create();
            WishlistItem::factory()->for($wishlist)->for($products[$index], 'product')->create();

            return $wishlist;
        });

        $orders = $users->values()->map(function (User $user, int $index) use ($addresses, $variants, $products): Order {
            $variant = $variants[$index];
            $subtotal = (int) ($variant->price ?? 149000);
            $status = ['pending', 'processing', 'packed', 'shipped', 'completed', 'cancelled'][$index % 6];
            $paymentStatus = $status === 'completed' ? 'paid' : ($status === 'cancelled' ? 'failed' : 'pending');
            $order = Order::factory()->for($user)->for($addresses[$index])->create([
                'order_number' => 'KB-SEED-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'status' => $status,
                'payment_status' => $paymentStatus,
                'shipping_status' => $status === 'shipped' || $status === 'completed' ? 'delivered' : 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => 15000,
                'total_amount' => $subtotal + 15000,
            ]);
            OrderItem::factory()->for($order)->for($variant, 'productVariant')->create(['product_name' => $products[$index]->name, 'variant_name' => $variant->format, 'sku' => $variant->sku, 'unit_price' => $subtotal, 'subtotal' => $subtotal]);
            $payment = Payment::factory()->for($order)->create(['amount' => $subtotal + 15000, 'status' => $paymentStatus]);
            PaymentWebhook::factory()->for($payment)->create(['reference' => $payment->reference]);
            Shipment::factory()->for($order)->create(['status' => $status === 'shipped' || $status === 'completed' ? 'delivered' : 'pending']);

            return $order;
        });

        Voucher::factory()->count(20)->create();
        Promotion::factory()->count(20)->create();

        $users->values()->each(function (User $user, int $index) use ($products, $orders): void {
            Review::factory()->for($user)->for($products[$index], 'product')->for($orders[$index], 'order')->create(['is_approved' => $index % 4 !== 0]);
            $conversation = ChatConversation::factory()->for($user)->create();
            ChatMessage::factory()->for($conversation, 'conversation')->for($user, 'sender')->create();
        });
    }
}
