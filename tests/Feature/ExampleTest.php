<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('the storefront product page returns a successful response', function () {
    $response = $this->get('/shop/the-quiet-atlas');

    $response->assertStatus(200);
});

test('the category, review, and chat pages are available', function () {
    $this->get('/categories')->assertOk();
    $this->get('/categories/fiction')->assertOk();
    $this->get('/reviews')->assertOk();
    $this->get('/chat')->assertOk();
});

test('a customer can add an item and reach checkout', function () {
    $this->post('/cart/items', [
        'slug' => 'the-quiet-atlas',
        'quantity' => 2,
    ])->assertRedirect('/cart');

    $this->get('/cart')->assertOk()->assertSee('The Quiet Atlas');
    $this->get('/checkout')->assertOk()->assertSee('Secure checkout');
});

test('the home, about, and not found pages render their customer shell', function () {
    $this->get('/')->assertOk()->assertSee('Find a story that stays.');
    $this->get('/about')->assertOk()->assertSee('Make room for a new point of view.');
    $this->get('/missing-page')->assertNotFound()->assertSee('This page took a different turn.');
});

test('a customer can register login and logout', function () {
    $this->post('/register', [
        'name' => 'Nadia Rahma',
        'email' => 'nadia@example.com',
        'phone' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => '1',
    ])->assertRedirect('/');

    $this->assertAuthenticated();

    $this->post('/logout')->assertRedirect('/');
    $this->assertGuest();

    $this->post('/login', [
        'email' => 'nadia@example.com',
        'password' => 'password123',
    ])->assertRedirect('/');

    $this->assertAuthenticated();
});
