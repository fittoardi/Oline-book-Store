<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'products' => $this->products(),
            'categories' => config('storefront.categories', []),
            'reviews' => config('storefront.reviews', []),
        ]);
    }

    public function shop(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:40'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'availability' => ['nullable', 'in:in_stock'],
            'sort' => ['nullable', 'in:newest,oldest,price_asc,price_desc,popular,rating'],
        ]);

        $products = collect($this->products())
            ->when($filters['q'] ?? null, fn ($items, $query) => $items->filter(fn ($product) => str_contains(strtolower($product['name'].' '.$product['category']), strtolower($query))))
            ->when($filters['category'] ?? null, fn ($items, $category) => $items->where('category', $category))
            ->when(isset($filters['min_price']), fn ($items) => $items->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($items) => $items->where('price', '<=', $filters['max_price']))
            ->when(($filters['availability'] ?? null) === 'in_stock', fn ($items) => $items->filter(fn ($product) => ($product['stock'] ?? 12) > 0));

        $products = match ($filters['sort'] ?? 'newest') {
            'oldest' => $products->sortBy('id'),
            'price_asc' => $products->sortBy('price'),
            'price_desc' => $products->sortByDesc('price'),
            'popular', 'rating' => $products->sortByDesc('rating'),
            default => $products->sortByDesc('id'),
        };

        return view('pages.catalog.shop', [
            'products' => $products->values(),
            'filters' => $filters,
            'categories' => config('storefront.categories', []),
        ]);
    }

    public function collection()
    {
        return view('pages.catalog.collection', ['products' => $this->products()]);
    }

    public function categories()
    {
        return view('categories.index', ['categories' => config('storefront.categories', [])]);
    }

    public function category(string $slug)
    {
        $category = collect(config('storefront.categories', []))->firstWhere('slug', $slug);

        abort_unless($category, 404);

        return view('categories.show', [
            'category' => $category,
            'products' => collect($this->products())->where('category', $category['name']),
        ]);
    }

    public function reviews()
    {
        return view('reviews.index', ['reviews' => config('storefront.reviews', [])]);
    }

    public function product(string $slug)
    {
        $products = collect($this->products());
        $product = $products->firstWhere('slug', $slug);

        abort_unless($product, 404);

        return view('pages.catalog.product-show', [
            'product' => $product,
            'relatedProducts' => $products->where('id', '!=', $product['id'])->take(3),
        ]);
    }

    private function products(): array
    {
        return config('storefront.products', []);
    }
}
