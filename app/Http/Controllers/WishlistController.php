<?php

namespace App\Http\Controllers;

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
                ->with(['primaryImage', 'category'])
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

    private function resolveWishlist(Request $request): ?Wishlist
    {
        if (! Auth::check()) {
            return null;
        }

        return Wishlist::firstOrCreate(['user_id' => Auth::id()]);
    }
}
