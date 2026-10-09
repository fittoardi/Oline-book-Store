<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $cart = $this->cartItems($request);

        return view('pages.customer.cart', [
            'cart' => $cart,
            'subtotal' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']),
        ]);
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = collect($this->products())->firstWhere('slug', $data['slug']);
        abort_unless($product, 404);

        $cart = $request->session()->get('cart', []);
        $cart[$product['slug']] = [
            'slug' => $product['slug'],
            'name' => $product['name'],
            'category' => $product['category'],
            'image' => $product['image'],
            'price' => $product['price'],
            'quantity' => ($cart[$product['slug']]['quantity'] ?? 0) + $data['quantity'],
        ];
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', $product['name'].' added to cart.');
    }

    public function updateCart(Request $request, string $slug)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        $cart = $request->session()->get('cart', []);

        abort_unless(isset($cart[$slug]), 404);
        $cart[$slug]['quantity'] = $data['quantity'];
        $request->session()->put('cart', $cart);

        return to_route('cart.index');
    }

    public function removeFromCart(Request $request, string $slug)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$slug]);
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Item removed from your cart.');
    }

    private function products(): array
    {
        return config('storefront.products', []);
    }

    private function cartItems(Request $request): array
    {
        return array_values($request->session()->get('cart', []));
    }
}
