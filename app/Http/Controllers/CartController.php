<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(public CartService $cartService) {}

    public function cart(Request $request)
    {
        $cart = $this->cartService->resolveCart($request);

        return view('pages.customer.cart', [
            'cart' => $cart,
            'items' => $cart->items()->with(['productVariant.product.primaryImage'])->get(),
            'subtotal' => $this->cartService->subtotal($cart),
        ]);
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required', 'string'],
            'format' => ['nullable', 'string', 'in:paperback,hardcover,gift_edition'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'buy_now' => ['nullable', 'boolean'],
        ]);

        $product = Product::query()
            ->where('slug', $data['slug'])
            ->where('is_active', true)
            ->firstOrFail();

        $cart = $this->cartService->resolveCart($request);

        try {
            $this->cartService->addItem($cart, $product, $data['format'] ?? 'paperback', $data['quantity']);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }

        if ($request->boolean('buy_now')) {
            return to_route('checkout.index');
        }

        return to_route('cart.index')->with('status', $product->name.' added to cart.');
    }

    public function updateCart(Request $request, string $slug)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'format' => ['nullable', 'string', 'in:paperback,hardcover,gift_edition'],
        ]);

        $cart = $this->cartService->resolveCart($request);

        try {
            $this->cartService->updateItem($cart, $slug, $data['format'] ?? null, $data['quantity']);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }

        return to_route('cart.index');
    }

    public function removeFromCart(Request $request, string $slug)
    {
        $cart = $this->cartService->resolveCart($request);
        $this->cartService->removeItem($cart, $slug, $request->input('format'));

        return to_route('cart.index')->with('status', 'Item removed from cart.');
    }

    public function resolveCart(Request $request): Cart
    {
        return $this->cartService->resolveCart($request);
    }

    public function subtotal(Cart $cart): int
    {
        return $this->cartService->subtotal($cart);
    }
}
