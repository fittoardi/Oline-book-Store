<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function wishlist(Request $request)
    {
        $slugs = $request->session()->get('wishlist', []);

        return view('pages.customer.wishlist', [
            'products' => collect($this->products())->whereIn('slug', $slugs)->values(),
        ]);
    }

    public function toggleWishlist(Request $request)
    {
        $data = $request->validate(['slug' => ['required', 'string']]);
        abort_unless(collect($this->products())->firstWhere('slug', $data['slug']), 404);

        $wishlist = $request->session()->get('wishlist', []);
        $wishlist = in_array($data['slug'], $wishlist, true)
            ? array_values(array_diff($wishlist, [$data['slug']]))
            : [...$wishlist, $data['slug']];
        $request->session()->put('wishlist', $wishlist);

        return back()->with('status', in_array($data['slug'], $wishlist, true) ? 'Saved to wishlist.' : 'Removed from wishlist.');
    }

    private function products(): array
    {
        return config('storefront.products', []);
    }
}
