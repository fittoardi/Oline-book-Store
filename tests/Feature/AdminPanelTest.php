<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guest is redirected to the filament login', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('customer cannot access the admin panel', function () {
    $customer = User::create([
        'name' => 'Customer',
        'email' => 'customer@example.com',
        'role' => 'customer',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($customer)->get('/admin')->assertForbidden();
});

test('admin can access the admin panel', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($admin)->get('/admin')->assertOk();
});

test('admin can manage products through the resource', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($admin);

    $this->get('/admin/products')->assertOk();
    $this->get('/admin/categories')->assertOk();
    $this->get('/admin/orders')->assertOk();
    $this->get('/admin/users')->assertOk();
    $this->get('/admin/vouchers')->assertOk();
    $this->get('/admin/reviews')->assertOk();
    $this->get('/admin/product-variants')->assertOk();
});
