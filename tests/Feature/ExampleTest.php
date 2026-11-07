<?php

use App\Models\Address;
use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function seedCatalog(): void
{
    $category = Category::create([
        'name' => 'Fiction',
        'slug' => 'fiction',
        'description' => 'Stories that stay with you after the last page.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'The Quiet Atlas',
        'slug' => 'the-quiet-atlas',
        'author' => 'Mira Larasati',
        'description' => 'A tender novel about maps, memory, and the places we call home.',
        'price' => 149000,
        'is_active' => true,
    ]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'KB-TEST-PB',
        'format' => 'paperback',
        'stock' => 40,
        'price' => 149000,
        'is_active' => true,
    ]);

    ProductImage::create([
        'product_id' => $product->id,
        'path' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=900&q=85',
        'alt_text' => 'Cover of The Quiet Atlas',
        'is_primary' => true,
    ]);

    $user = User::create([
        'name' => 'Nadia Rahma',
        'email' => 'nadia@example.com',
        'phone' => '081234567890',
        'role' => 'customer',
        'password' => Hash::make('password'),
    ]);

    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'KB-TEST-0001',
        'status' => 'completed',
        'payment_status' => 'paid',
        'shipping_status' => 'delivered',
        'subtotal' => 149000,
        'discount_amount' => 0,
        'shipping_cost' => 15000,
        'total_amount' => 164000,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_name' => $variant->format,
        'sku' => $variant->sku,
        'quantity' => 1,
        'unit_price' => 149000,
        'subtotal' => 149000,
    ]);

    Review::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'order_id' => $order->id,
        'rating' => 5,
        'comment' => 'A beautiful story.',
        'is_approved' => true,
    ]);
}

test('the application returns a successful response', function () {
    seedCatalog();

    $this->get('/')->assertStatus(200);
});

test('the storefront product page returns a successful response', function () {
    seedCatalog();

    $this->get('/shop/the-quiet-atlas')->assertStatus(200);
});

test('the category, review, and chat pages are available', function () {
    seedCatalog();

    $this->get('/categories')->assertOk();
    $this->get('/categories/fiction')->assertOk();
    $this->get('/reviews')->assertOk();
    $this->get('/chat')->assertOk();
    $this->get('/collection')->assertOk();
    $this->get('/help')->assertOk();
});

test('a customer can add an item and reach checkout', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $this->actingAs($user)
        ->post('/cart/items', [
            'slug' => 'the-quiet-atlas',
            'quantity' => 2,
        ])
        ->assertRedirect('/cart');

    $this->actingAs($user)->get('/cart')->assertOk()->assertSee('The Quiet Atlas');
    $this->actingAs($user)->get('/checkout')->assertOk()->assertSee('Secure checkout');
});

test('the home, about, and not found pages render their customer shell', function () {
    seedCatalog();

    $this->get('/')->assertOk()->assertSee('Find a story that stays.');
    $this->get('/about')->assertOk()->assertSee('Make room for a new point of view.');
    $this->get('/missing-page')->assertNotFound()->assertSee('This page took a different turn.');
});

test('a customer can register login and logout', function () {
    seedCatalog();

    $this->post('/register', [
        'name' => 'Nadia Rahma',
        'email' => 'nadia2@example.com',
        'phone' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => '1',
    ])->assertRedirect('/');

    $this->assertAuthenticated();

    $this->post('/logout')->assertRedirect('/');
    $this->assertGuest();

    $this->post('/login', [
        'email' => 'nadia2@example.com',
        'password' => 'password123',
    ])->assertRedirect('/');

    $this->assertAuthenticated();
});

test('a customer can place an order from the cart', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $this->actingAs($user);

    $this->post('/cart/items', ['slug' => 'the-quiet-atlas', 'quantity' => 1])->assertRedirect('/cart');

    $this->post('/checkout', [
        'name' => 'Nadia Rahma',
        'phone' => '081234567890',
        'address' => 'Jl. Melati No. 1, Jakarta Selatan',
        'payment' => 'COD',
    ])->assertRedirect('/orders');

    $order = $user->orders()->where('order_number', '!=', 'KB-TEST-0001')->latest()->first();
    expect($order)->not->toBeNull();
    expect($order->items)->toHaveCount(1);
    expect($order->payment)->not->toBeNull();
});

test('a logged in customer can toggle wishlist', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $this->actingAs($user)
        ->post('/wishlist/toggle', ['slug' => 'the-quiet-atlas'])
        ->assertRedirect();

    expect($user->wishlist->items()->count())->toBe(1);

    $this->actingAs($user)
        ->post('/wishlist/toggle', ['slug' => 'the-quiet-atlas'])
        ->assertRedirect();

    expect($user->fresh()->wishlist->items()->count())->toBe(0);
});

test('guest routes redirect to login when unauthenticated', function () {
    $this->get('/checkout')->assertRedirect('/login');
    $this->get('/orders')->assertRedirect('/login');
    $this->get('/profile')->assertRedirect('/login');
    $this->get('/wishlist')->assertRedirect('/login');
    $this->get('/addresses')->assertRedirect('/login');
    $this->post('/checkout')->assertRedirect('/login');
    $this->post('/addresses')->assertRedirect('/login');
    $this->post('/wishlist/toggle', ['slug' => 'the-quiet-atlas'])->assertRedirect('/login');
});

test('order pages are scoped to the owner', function () {
    seedCatalog();

    $owner = User::where('email', 'nadia@example.com')->first();
    $other = User::factory()->create(['role' => 'customer']);

    $this->actingAs($other)->get('/orders/KB-TEST-0001')->assertNotFound();
    $this->actingAs($owner)->get('/orders/KB-TEST-0001')->assertOk();
});

test('a customer can move a book from wishlist to cart', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $this->actingAs($user)->post('/wishlist', ['slug' => 'the-quiet-atlas']);
    expect($user->wishlist->items()->count())->toBe(1);

    $this->actingAs($user)
        ->post('/wishlist/the-quiet-atlas/move-to-cart')
        ->assertRedirect('/cart');

    expect($user->fresh()->wishlist->items()->count())->toBe(0);
    $this->actingAs($user)->get('/cart')->assertOk()->assertSee('The Quiet Atlas');
});

test('free shipping is applied when subtotal meets the threshold', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();
    $this->actingAs($user);

    // 149,000 * 3 = 447,000 >= 300,000 threshold
    $this->post('/cart/items', ['slug' => 'the-quiet-atlas', 'quantity' => 3]);

    $this->get('/checkout')->assertOk()->assertSee('FREE');

    $this->post('/checkout', [
        'name' => 'Nadia Rahma',
        'phone' => '081234567890',
        'address' => 'Jl. Melati No. 1, Jakarta Selatan',
        'payment' => 'Bank transfer',
    ])->assertRedirect('/orders');

    $order = $user->orders()->where('order_number', '!=', 'KB-TEST-0001')->latest()->first();
    expect((float) $order->shipping_cost)->toBe(0.0);
    expect((float) $order->total_amount)->toBe(447000.0);
});

test('a customer can apply a voucher during checkout and receive a discount', function () {
    seedCatalog();

    Voucher::create([
        'code' => 'KEMBANG10',
        'name' => 'Diskon 10%',
        'type' => 'percentage',
        'value' => 10,
        'minimum_purchase' => 100000,
        'maximum_discount' => 50000,
        'is_active' => true,
    ]);

    $user = User::where('email', 'nadia@example.com')->first();
    $this->actingAs($user);

    $this->post('/cart/items', ['slug' => 'the-quiet-atlas', 'quantity' => 1]); // 149,000

    $this->post('/checkout/voucher', ['voucher_code' => 'KEMBANG10'])
        ->assertRedirect();

    $this->get('/checkout')
        ->assertOk()
        ->assertSee('KEMBANG10')
        ->assertSee('14.900'); // 10% of 149,000 is 14,900

    $this->post('/checkout', [
        'name' => 'Nadia Rahma',
        'phone' => '081234567890',
        'address' => 'Jl. Melati No. 1, Jakarta Selatan',
        'payment' => 'Bank transfer',
        'voucher_code' => 'KEMBANG10',
    ])->assertRedirect('/orders');

    $order = $user->orders()->where('order_number', '!=', 'KB-TEST-0001')->latest()->first();
    expect((float) $order->discount_amount)->toBe(14900.0);
});

test('a verified buyer with a completed order can submit a review awaiting moderation', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();
    $product = Product::where('slug', 'the-quiet-atlas')->first();

    // Remove seeded review so user can review anew
    Review::query()->where('user_id', $user->id)->delete();

    $this->actingAs($user)
        ->post('/reviews', [
            'slug' => 'the-quiet-atlas',
            'rating' => 5,
            'comment' => 'Buku yang sangat mengesankan dan penuh perenungan.',
        ])
        ->assertRedirect();

    $review = Review::where('user_id', $user->id)->where('product_id', $product->id)->first();
    expect($review)->not->toBeNull();
    expect($review->is_approved)->toBeFalse();
    expect($review->comment)->toBe('Buku yang sangat mengesankan dan penuh perenungan.');
});

test('an unverified user or user without completed order cannot review a product', function () {
    seedCatalog();

    $newUser = User::factory()->create(['role' => 'customer']);

    $this->actingAs($newUser)
        ->post('/reviews', [
            'slug' => 'the-quiet-atlas',
            'rating' => 5,
            'comment' => 'Saya belum beli tapi mau coba ulas.',
        ])
        ->assertSessionHasErrors(['review']);

    expect(Review::where('user_id', $newUser->id)->exists())->toBeFalse();
});

test('a customer can set an address as default', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $address1 = Address::create([
        'user_id' => $user->id,
        'label' => 'Kantor',
        'recipient_name' => 'Nadia',
        'phone' => '081234567890',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta Selatan',
        'district' => '-',
        'postal_code' => '12345',
        'full_address' => 'Gedung A Lt. 3',
        'is_default' => true,
    ]);

    $address2 = Address::create([
        'user_id' => $user->id,
        'label' => 'Rumah',
        'recipient_name' => 'Nadia',
        'phone' => '081234567890',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta Barat',
        'district' => '-',
        'postal_code' => '11223',
        'full_address' => 'Jl. Bunga No. 5',
        'is_default' => false,
    ]);

    $this->actingAs($user)
        ->post("/addresses/{$address2->id}/default")
        ->assertRedirect();

    expect($address2->fresh()->is_default)->toBeTrue();
    expect($address1->fresh()->is_default)->toBeFalse();
});

test('a customer can send a chat message to customer care', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $this->actingAs($user)
        ->post('/chat', ['message' => 'Halo, apakah edisi hardcover The Quiet Atlas akan restock?'])
        ->assertRedirect('/chat');

    $conversation = ChatConversation::where('user_id', $user->id)->first();
    expect($conversation)->not->toBeNull();
    expect($conversation->messages()->count())->toBe(1);
    expect($conversation->messages()->first()->message)->toContain('hardcover');
});

test('buy now button directs customer directly to checkout', function () {
    seedCatalog();

    $user = User::where('email', 'nadia@example.com')->first();

    $this->actingAs($user)
        ->post('/cart/items', [
            'slug' => 'the-quiet-atlas',
            'quantity' => 1,
            'buy_now' => 1,
        ])
        ->assertRedirect('/checkout');
});
