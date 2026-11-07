<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function wishlist(Request $request)
    {
        $wishlist = $this->resolveWishlist($request);
        $products = collect();

        if ($wishlist) {
            $products = Product::query()
                ->with(['primaryImage', 'category', 'variants'])
                ->whereIn('id', $wishlist->items()->pluck('product_id'))
                ->where('is_active', true)
                ->latest()
                ->get();
        }

        return view('pages.customer.wishlist', ['products' => $products]);
    }

    public function toggleWishlist(Request $request)
    {
        $data = $request->validate(['slug' => ['required', 'string']]);

        $product = Product::query()
            ->where('slug', $data['slug'])
            ->where('is_active', true)
            ->firstOrFail();

        $wishlist = $this->resolveWishlist($request);

        $item = WishlistItem::query()
            ->where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->delete();

            return back()->with('status', 'Removed from wishlist.');
        }

        WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $product->id,
        ]);

        return back()->with('status', 'Saved to wishlist.');
    }

    public function moveToCart(Request $request, string $slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $variant = $product->variants()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->first() ?? $product->variants()->where('is_active', true)->first();

        if (! $variant) {
            return back()->withErrors(['cart' => 'Buku ini sedang tidak tersedia.']);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active'],
            ['session_id' => $request->session()->getId()],
        );

        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
        ]);

        $newQty = ($cartItem->quantity ?? 0) + 1;
        if ($variant->availableStock() < $newQty) {
            return back()->withErrors(['cart' => 'Stok buku ini tidak mencukupi untuk ditambahkan ke keranjang.']);
        }

        $cartItem->quantity = $newQty;
        $cartItem->unit_price = $variant->price ?? $product->price;
        $cartItem->save();

        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        if ($wishlist) {
            WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('product_id', $product->id)
                ->delete();
        }

        return to_route('cart.index')->with('status', $product->name.' dipindahkan ke keranjang.');
    }

    private function resolveWishlist(Request $request): ?Wishlist
    {
        if (! Auth::check()) {
            return null;
        }

        return Wishlist::firstOrCreate(['user_id' => Auth::id()]);
    }
}
