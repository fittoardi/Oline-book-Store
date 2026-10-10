<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $cart = $this->resolveCart($request);

        return view('pages.customer.cart', [
            'cart' => $cart,
            'items' => $cart->items()->with(['productVariant.product.primaryImage'])->get(),
            'subtotal' => $this->subtotal($cart),
        ]);
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required', 'string'],
            'format' => ['nullable', 'string', 'in:paperback,hardcover,gift_edition'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::query()
            ->where('slug', $data['slug'])
            ->where('is_active', true)
            ->firstOrFail();

        $variant = ProductVariant::query()
            ->where('product_id', $product->id)
            ->where('format', $data['format'] ?? 'paperback')
            ->where('is_active', true)
            ->firstOrFail();

        $cart = $this->resolveCart($request);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        $newQuantity = ($item?->quantity ?? 0) + $data['quantity'];

        if ($variant->availableStock() < $newQuantity) {
            return back()->withErrors(['quantity' => 'Total quantity melebihi stok yang tersedia ('.$variant->availableStock().').'])->withInput();
        }

        CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_variant_id' => $variant->id],
            ['quantity' => $newQuantity, 'unit_price' => $variant->price ?? $product->price],
        );

        return to_route('cart.index')->with('status', $product->name.' added to cart.');
    }

    public function updateCart(Request $request, string $slug)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'format' => ['nullable', 'string', 'in:paperback,hardcover,gift_edition'],
        ]);

        $cart = $this->resolveCart($request);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereHas('productVariant.product', fn ($q) => $q->where('slug', $slug))
            ->when($data['format'] ?? null, function ($q, $format) {
                $q->whereHas('productVariant', fn ($vq) => $vq->where('format', $format));
            })
            ->firstOrFail();

        if ($item->productVariant->availableStock() < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Quantity melebihi stok yang tersedia ('.$item->productVariant->availableStock().').'])->withInput();
        }

        $item->update(['quantity' => $data['quantity']]);

        return to_route('cart.index');
    }

    public function removeFromCart(Request $request, string $slug)
    {
        $cart = $this->resolveCart($request);

        CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereHas('productVariant.product', fn ($q) => $q->where('slug', $slug))
            ->each(function (CartItem $item) {
                $item->delete();
            });

        return to_route('cart.index')->with('status', 'Item removed from your cart.');
    }

    private function resolveCart(Request $request): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'active'],
                ['session_id' => $request->session()->getId()],
            );
        }

        $cart = Cart::query()
            ->where('session_id', $request->session()->getId())
            ->where('status', 'active')
            ->first();

        if ($cart) {
            return $cart;
        }

        return Cart::create([
            'session_id' => $request->session()->getId(),
            'status' => 'active',
        ]);
    }

    private function subtotal(Cart $cart): int
    {
        return (int) CartItem::query()
            ->where('cart_id', $cart->id)
            ->get()
            ->sum(fn (CartItem $item) => $item->unit_price * $item->quantity);
    }
}
