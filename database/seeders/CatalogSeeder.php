<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $categories = $this->seedCategories();
        $products = $this->seedProducts($categories);
        $this->seedAddresses();
        $this->seedWishlists($products);
        $this->seedCarts($products);
        $orders = $this->seedOrders($products);
        $this->seedReviews($products, $orders);
        $this->seedVouchersAndPromotions();
        $this->seedChats();
        $this->seedContactMessages();
    }

    private function seedUsers(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kembangbooks.test'],
            ['name' => 'Kembang Books Admin', 'phone' => '081200000000', 'role' => 'admin', 'password' => Hash::make('password')],
        );

        User::updateOrCreate(
            ['email' => 'customer@kembangbooks.test'],
            ['name' => 'Nadia Rahma', 'phone' => '081234567890', 'role' => 'customer', 'password' => Hash::make('password')],
        );

        User::factory()->count(18)->create(['role' => 'customer']);
    }

    /**
     * @return Collection<int, Category>
     */
    private function seedCategories(): Collection
    {
        $genres = [
            ['name' => 'Fiction', 'slug' => 'fiction', 'description' => 'Stories that stay with you after the last page.', 'sort_order' => 1],
            ['name' => 'Nonfiction', 'slug' => 'nonfiction', 'description' => 'Ideas, essays, and lived wisdom for curious minds.', 'sort_order' => 2],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Practical thinking for better work and better ideas.', 'sort_order' => 3],
            ['name' => 'Children', 'slug' => 'children', 'description' => 'Bright worlds and gentle adventures for young readers.', 'sort_order' => 4],
            ['name' => 'Poetry', 'slug' => 'poetry', 'description' => 'Short lines for long days — verse, longing, and quiet light.', 'sort_order' => 5],
        ];

        return collect($genres)->map(function (array $genre): Category {
            return Category::updateOrCreate(
                ['slug' => $genre['slug']],
                ['name' => $genre['name'], 'description' => $genre['description'], 'is_active' => true, 'sort_order' => $genre['sort_order']],
            );
        });
    }

    /**
     * @param  Collection<int, Category>  $categories
     * @return Collection<int, Product>
     */
    private function seedProducts(Collection $categories): Collection
    {
        $fiction = $categories->firstWhere('slug', 'fiction');
        $nonfiction = $categories->firstWhere('slug', 'nonfiction');
        $business = $categories->firstWhere('slug', 'business');
        $children = $categories->firstWhere('slug', 'children');
        $poetry = $categories->firstWhere('slug', 'poetry');

        $catalog = [
            ['category' => $fiction, 'name' => 'The Quiet Atlas', 'author' => 'Mira Larasati', 'publisher' => 'Penerbit Senja', 'price' => 149000, 'compare_price' => 179000, 'pages' => 312, 'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=900&q=85', 'description' => 'A tender novel about maps, memory, and the places we call home.', 'is_featured' => true, 'is_new' => true],
            ['category' => $fiction, 'name' => 'Letters From Tomorrow', 'author' => 'Adhitya Pranata', 'publisher' => 'Penerbit Senja', 'price' => 119000, 'compare_price' => null, 'pages' => 248, 'image' => 'https://images.unsplash.com/photo-1511108690759-009324a90311?auto=format&fit=crop&w=900&q=85', 'description' => 'A hopeful collection of stories about choices, time, and second chances.', 'is_featured' => false, 'is_new' => true],
            ['category' => $nonfiction, 'name' => 'The Useful Day', 'author' => 'Rania Kusuma', 'publisher' => 'Penerbit Pagi', 'price' => 159000, 'compare_price' => 189000, 'pages' => 224, 'image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=900&q=85', 'description' => 'Small, practical ways to make room for focus, rest, and meaningful work.', 'is_featured' => true, 'is_new' => false],
            ['category' => $business, 'name' => 'Build Better Ideas', 'author' => 'Bagas Wicaksana', 'publisher' => 'Penerbit Kerja', 'price' => 99000, 'compare_price' => null, 'pages' => 276, 'image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=85', 'description' => 'A clear field guide for turning early thoughts into useful products and teams.', 'is_featured' => false, 'is_new' => false],
            ['category' => $children, 'name' => 'The Wild Garden', 'author' => 'Sari Melati', 'publisher' => 'Penerbit Pelangi', 'price' => 129000, 'compare_price' => null, 'pages' => 64, 'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=85', 'description' => 'An illustrated journey through a garden where every small creature has a story.', 'is_featured' => false, 'is_new' => true],
            ['category' => $children, 'name' => 'Stories for Slow Sundays', 'author' => 'Sari Melati', 'publisher' => 'Penerbit Pelangi', 'price' => 109000, 'compare_price' => null, 'pages' => 72, 'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=900&q=85', 'description' => 'Warm, illustrated stories made for reading together at the end of a long week.', 'is_featured' => false, 'is_new' => false],
            ['category' => $poetry, 'name' => 'Lullabies for Loud Minds', 'author' => 'Dewi Anggraini', 'publisher' => 'Penerbit Malam', 'price' => 89000, 'compare_price' => null, 'pages' => 128, 'image' => 'https://images.unsplash.com/photo-1474932430478-367dbb6832c1?auto=format&fit=crop&w=900&q=85', 'description' => 'Poems for the hours when thoughts run faster than sleep.', 'is_featured' => false, 'is_new' => true],
            ['category' => $fiction, 'name' => 'Monsoon Letters', 'author' => 'Larasati', 'publisher' => 'Penerbit Hujan', 'price' => 135000, 'compare_price' => 155000, 'pages' => 296, 'image' => 'https://images.unsplash.com/photo-1519791883288-dc8bd696e667?auto=format&fit=crop&w=900&q=85', 'description' => 'Twelve letters that arrive one wet season too late.', 'is_featured' => true, 'is_new' => false],
            ['category' => $nonfiction, 'name' => 'The Long Read', 'author' => 'Prof. Hendra Wijaya', 'publisher' => 'Penerbit Pagi', 'price' => 139000, 'compare_price' => null, 'pages' => 344, 'image' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?auto=format&fit=crop&w=900&q=85', 'description' => 'Essays on attention, books, and the slow art of thinking.', 'is_featured' => false, 'is_new' => false],
            ['category' => $business, 'name' => 'Small Team, Big Map', 'author' => 'Indira Chen', 'publisher' => 'Penerbit Kerja', 'price' => 119000, 'compare_price' => null, 'pages' => 240, 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=900&q=85', 'description' => 'How small teams ship thoughtful work without losing the plot.', 'is_featured' => false, 'is_new' => true],
            ['category' => $poetry, 'name' => 'Notes on Rain', 'author' => 'Bayu Setiawan', 'publisher' => 'Penerbit Malam', 'price' => 79000, 'compare_price' => null, 'pages' => 96, 'image' => 'https://images.unsplash.com/photo-1519791883288-dc8bd696e667?auto=format&fit=crop&w=900&q=85', 'description' => 'A pocket notebook of rain, roads, and returning home.', 'is_featured' => false, 'is_new' => false],
            ['category' => $children, 'name' => 'The Moonkeeper', 'author' => 'Putri Halim', 'publisher' => 'Penerbit Pelangi', 'price' => 99000, 'compare_price' => 119000, 'pages' => 56, 'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=900&q=85', 'description' => 'A bedtime tale about the tiny person who sweeps the moon every night.', 'is_featured' => false, 'is_new' => false],
        ];

        return collect($catalog)->map(function (array $book): Product {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($book['name'])],
                [
                    'category_id' => $book['category']->id,
                    'name' => $book['name'],
                    'author' => $book['author'],
                    'isbn' => '978'.fake()->unique()->numerify('##########'),
                    'publisher' => $book['publisher'],
                    'page_count' => $book['pages'],
                    'language' => 'id',
                    'description' => $book['description'],
                    'price' => $book['price'],
                    'compare_price' => $book['compare_price'],
                    'is_active' => true,
                    'is_featured' => $book['is_featured'],
                    'is_new' => $book['is_new'],
                    'views_count' => fake()->numberBetween(120, 4200),
                ],
            );

            $this->seedVariants($product, $book['price']);
            $this->seedPrimaryImage($product, $book['name'], $book['image']);

            return $product;
        });
    }

    private function seedVariants(Product $product, int $basePrice): void
    {
        $editions = [
            ['format' => 'paperback', 'stock' => 40, 'price' => $basePrice],
            ['format' => 'hardcover', 'stock' => 12, 'price' => $basePrice + 45000],
        ];

        foreach ($editions as $edition) {
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'format' => $edition['format']],
                ['sku' => 'KB-'.strtoupper(Str::random(6)).'-'.$edition['format'], 'stock' => $edition['stock'], 'reserved_stock' => 0, 'price' => $edition['price'], 'is_active' => true],
            );
        }

        ProductVariant::updateOrCreate(
            ['product_id' => $product->id, 'format' => 'gift_edition'],
            ['sku' => 'KB-'.strtoupper(Str::random(6)).'-gift', 'stock' => 0, 'reserved_stock' => 0, 'price' => $basePrice + 95000, 'is_active' => true],
        );
    }

    private function seedPrimaryImage(Product $product, string $name, string $path): void
    {
        ProductImage::updateOrCreate(
            ['product_id' => $product->id, 'is_primary' => true],
            ['path' => $path, 'alt_text' => 'Cover of '.$name, 'sort_order' => 0],
        );
    }

    private function seedAddresses(): void
    {
        $users = User::where('role', 'customer')->get();

        foreach ($users as $user) {
            Address::updateOrCreate(
                ['user_id' => $user->id, 'label' => 'Rumah'],
                [
                    'recipient_name' => $user->name,
                    'phone' => $user->phone ?? '081200000000',
                    'province' => 'DKI Jakarta',
                    'city' => 'Jakarta Selatan',
                    'district' => 'Kebayoran Baru',
                    'postal_code' => '12190',
                    'full_address' => 'Jl. Melati No. '.fake()->numberBetween(1, 80).', Kebayoran Baru, Jakarta Selatan',
                    'is_default' => true,
                ],
            );
        }
    }

    /**
     * @param  Collection<int, Product>  $products
     */
    private function seedWishlists(Collection $products): void
    {
        $users = User::where('role', 'customer')->get();

        foreach ($users->values() as $index => $user) {
            $wishlist = Wishlist::updateOrCreate(['user_id' => $user->id]);
            $product = $products[$index % $products->count()];

            WishlistItem::updateOrCreate(
                ['wishlist_id' => $wishlist->id, 'product_id' => $product->id],
            );
        }
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return Collection<int, Cart>
     */
    private function seedCarts(Collection $products): Collection
    {
        $users = User::where('role', 'customer')->get();
        $variants = ProductVariant::whereIn('product_id', $products->pluck('id'))->where('stock', '>', 0)->get();

        return $users->values()->map(function (User $user, int $index) use ($variants): Cart {
            $cart = Cart::updateOrCreate(
                ['user_id' => $user->id, 'status' => 'active'],
                ['session_id' => null],
            );

            $variant = $variants[$index % $variants->count()];
            CartItem::updateOrCreate(
                ['cart_id' => $cart->id, 'product_variant_id' => $variant->id],
                ['quantity' => 1, 'unit_price' => $variant->price ?? $variant->product->price],
            );

            return $cart;
        });
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return Collection<int, Order>
     */
    private function seedOrders(Collection $products): Collection
    {
        $users = User::where('role', 'customer')->get();
        $statuses = ['pending', 'processing', 'packed', 'shipped', 'completed', 'cancelled'];

        return $users->values()->map(function (User $user, int $index) use ($products, $statuses): Order {
            $product = $products[$index % $products->count()];
            $variant = $product->variants()->where('stock', '>', 0)->first() ?? $product->variants()->first();
            $address = $user->addresses()->first();

            $status = $statuses[$index % count($statuses)];
            $paymentStatus = match ($status) {
                'completed' => 'paid',
                'cancelled' => 'failed',
                default => 'pending',
            };

            $unitPrice = (int) ($variant?->price ?? $product->price);
            $shippingCost = 15000;
            $subtotal = $unitPrice;
            $total = $subtotal + $shippingCost;
            $tracking = 'JNE'.fake()->numerify('##########');

            $order = Order::updateOrCreate(
                ['order_number' => 'KB-SEED-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $user->id,
                    'address_id' => $address?->id,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'shipping_status' => in_array($status, ['shipped', 'completed'], true) ? 'delivered' : 'pending',
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'shipping_cost' => $shippingCost,
                    'total_amount' => $total,
                    'shipping_courier' => 'JNE',
                    'tracking_number' => $tracking,
                ],
            );

            OrderItem::updateOrCreate(
                ['order_id' => $order->id, 'product_variant_id' => $variant?->id],
                ['product_name' => $product->name, 'variant_name' => $variant?->format, 'sku' => $variant?->sku, 'quantity' => 1, 'unit_price' => $unitPrice, 'subtotal' => $unitPrice],
            );

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                ['provider' => 'tripay', 'reference' => 'T-'.strtoupper(Str::random(10)), 'merchant_ref' => 'KB-SEED-'.$order->id, 'method' => 'Bank transfer', 'status' => $paymentStatus, 'amount' => $total],
            );

            Shipment::updateOrCreate(
                ['order_id' => $order->id],
                ['courier' => 'JNE', 'service' => 'REG', 'tracking_number' => $tracking, 'shipping_cost' => $shippingCost, 'estimated_delivery' => now()->addDays(3)->toDateString(), 'status' => in_array($status, ['shipped', 'completed'], true) ? 'delivered' : 'pending'],
            );

            return $order;
        });
    }

    /**
     * @param  Collection<int, Product>  $products
     * @param  Collection<int, Order>  $orders
     */
    private function seedReviews(Collection $products, Collection $orders): void
    {
        $reviews = [
            ['rating' => 5, 'product' => 'The Quiet Atlas', 'quote' => 'A beautiful story and the paper quality feels wonderful. It arrived carefully packed.'],
            ['rating' => 5, 'product' => 'The Useful Day', 'quote' => 'Clear, thoughtful, and genuinely useful. I keep coming back to a few pages every week.'],
            ['rating' => 4, 'product' => 'The Wild Garden', 'quote' => 'The illustrations are lovely and it has become our favourite bedtime book.'],
        ];

        $users = User::where('role', 'customer')->get();

        foreach ($reviews as $index => $review) {
            $product = $products->firstWhere('name', $review['product']);
            $user = $users->get($index);
            $order = $orders->get($index);

            if (! $product || ! $user || ! $order) {
                continue;
            }

            Review::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $product->id, 'order_id' => $order->id],
                ['rating' => $review['rating'], 'comment' => $review['quote'], 'is_approved' => true],
            );
        }

        foreach ($users->skip(3)->take(3) as $index => $user) {
            $product = $products->get($index);
            $order = $orders->get($index);

            if (! $product || ! $order) {
                continue;
            }

            Review::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $product->id, 'order_id' => $order->id],
                ['rating' => fake()->numberBetween(3, 5), 'comment' => fake()->sentence(10), 'is_approved' => false],
            );
        }
    }

    private function seedVouchersAndPromotions(): void
    {
        Voucher::updateOrCreate(
            ['code' => 'KEMBANG10'],
            ['name' => 'Diskon 10%', 'type' => 'percentage', 'value' => 10, 'minimum_purchase' => 100000, 'maximum_discount' => 50000, 'usage_limit' => 500, 'used_count' => 12, 'starts_at' => now()->subDays(10), 'expires_at' => now()->addDays(60), 'is_active' => true],
        );

        Voucher::updateOrCreate(
            ['code' => 'BACAHEMAT'],
            ['name' => 'Potongan Rp25.000', 'type' => 'fixed', 'value' => 25000, 'minimum_purchase' => 200000, 'usage_limit' => 200, 'used_count' => 3, 'starts_at' => now()->subDays(5), 'expires_at' => now()->addDays(30), 'is_active' => true],
        );

        Voucher::updateOrCreate(
            ['code' => 'EXPIRED2025'],
            ['name' => 'Promo kedaluwarsa', 'type' => 'percentage', 'value' => 15, 'minimum_purchase' => 0, 'starts_at' => now()->subDays(60), 'expires_at' => now()->subDay(), 'is_active' => false],
        );

        Promotion::updateOrCreate(
            ['name' => 'Weekend Book Sale'],
            ['type' => 'percentage', 'value' => 10, 'minimum_purchase' => 150000, 'starts_at' => now()->subDay(), 'expires_at' => now()->addDays(30), 'is_active' => true],
        );

        Promotion::updateOrCreate(
            ['name' => 'Back to School Children Books'],
            ['type' => 'percentage', 'value' => 20, 'minimum_purchase' => 100000, 'starts_at' => now()->subDays(2), 'expires_at' => now()->addDays(45), 'is_active' => true],
        );
    }

    private function seedChats(): void
    {
        $users = User::where('role', 'customer')->take(5)->get();

        foreach ($users as $user) {
            $conversation = ChatConversation::updateOrCreate(
                ['user_id' => $user->id, 'status' => 'open'],
                ['last_message_at' => now()],
            );

            ChatMessage::updateOrCreate(
                ['conversation_id' => $conversation->id, 'sender_id' => $user->id],
                ['message' => 'Halo, saya mencari rekomendasi buku fiksi yang ringan untuk dibaca saat perjalanan.'],
            );
        }
    }

    private function seedContactMessages(): void
    {
        ContactMessage::updateOrCreate(
            ['email' => 'nadia@example.com', 'subject' => 'Rekomendasi buku fiksi'],
            [
                'name' => 'Nadia Rahma',
                'message' => 'Halo, saya mencari rekomendasi novel ringan untuk dibaca saat perjalanan panjang. Ada saran?',
                'status' => 'new',
            ],
        );

        ContactMessage::updateOrCreate(
            ['email' => 'alya@example.com', 'subject' => 'Status pengiriman'],
            [
                'name' => 'Alya Putri',
                'message' => 'Pesanan saya KB-SEED-0002 sudah berapa hari pengirimannya? Mohon update.',
                'status' => 'handled',
                'handled_at' => now()->subDay(),
            ],
        );
    }
}
