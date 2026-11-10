<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Resolve the active cart for an authenticated user or guest session.
     */
    public function resolveCart(Request $request): Cart
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'active'],
                ['session_id' => $request->session()->getId()],
            );

            $sessionId = $request->session()->getId();
            $guestCart = Cart::query()
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->where('status', 'active')
                ->first();

            if ($guestCart && $guestCart->id !== $cart->id) {
                $this->mergeCarts($guestCart, $cart);
            }

            return $cart;
        }

        $sessionId = $request->session()->getId();

        return Cart::firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null, 'status' => 'active'],
            ['status' => 'active'],
        );
    }

    /**
     * Merge guest cart items into user cart.
     */
    public function mergeCarts(Cart $guestCart, Cart $userCart): void
    {
        foreach ($guestCart->items as $guestItem) {
            $userItem = CartItem::query()
                ->where('cart_id', $userCart->id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($userItem) {
                $maxStock = $guestItem->productVariant ? $guestItem->productVariant->availableStock() : 99;
                $newQuantity = min($userItem->quantity + $guestItem->quantity, $maxStock);
                $userItem->update(['quantity' => $newQuantity]);
                $guestItem->delete();
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }

    /**
     * Calculate cart subtotal.
     */
    public function subtotal(Cart $cart): int
    {
        return (int) CartItem::query()
            ->where('cart_id', $cart->id)
            ->get()
            ->sum(fn (CartItem $item) => $item->unit_price * $item->quantity);
    }

    /**
     * Add product variant item to cart with stock validation.
     */
    public function addItem(Cart $cart, Product $product, ?string $format, int $quantity): CartItem
    {
        $variant = ProductVariant::query()
            ->where('product_id', $product->id)
            ->where('format', $format ?? 'paperback')
            ->where('is_active', true)
            ->firstOrFail();

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        $newQuantity = ($item?->quantity ?? 0) + $quantity;

        if ($variant->availableStock() < $newQuantity) {
            throw new \InvalidArgumentException('Total quantity melebihi stok yang tersedia ('.$variant->availableStock().').');
        }

        return CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_variant_id' => $variant->id],
            ['quantity' => $newQuantity, 'unit_price' => $variant->price ?? $product->price],
        );
    }

    /**
     * Update quantity for a cart item.
     */
    public function updateItem(Cart $cart, string $slug, ?string $format, int $quantity): CartItem
    {
        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereHas('productVariant.product', fn ($q) => $q->where('slug', $slug))
            ->when($format, fn ($q, $fmt) => $q->whereHas('productVariant', fn ($vq) => $vq->where('format', $fmt)))
            ->firstOrFail();

        if ($item->productVariant->availableStock() < $quantity) {
            throw new \InvalidArgumentException('Quantity melebihi stok yang tersedia ('.$item->productVariant->availableStock().').');
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(Cart $cart, string $slug, ?string $format = null): void
    {
        CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereHas('productVariant.product', fn ($q) => $q->where('slug', $slug))
            ->when($format, fn ($q, $fmt) => $q->whereHas('productVariant', fn ($vq) => $vq->where('format', $fmt)))
            ->delete();
    }
}
