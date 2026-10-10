<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
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

    ProductVariant::create([
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
