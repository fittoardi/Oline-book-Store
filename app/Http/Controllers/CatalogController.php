<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'products' => Product::query()
                ->with(['category', 'primaryImage'])
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get(),
            'categories' => Category::query()
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'reviews' => Review::query()
                ->with(['user', 'product'])
                ->where('is_approved', true)
                ->latest()
                ->take(3)
                ->get(),
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

        $products = Product::query()
            ->with(['category', 'primaryImage'])
            ->where('is_active', true)
            ->when($filters['q'] ?? null, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', '%'.$term.'%')
                        ->orWhere('author', 'like', '%'.$term.'%')
                        ->orWhere('description', 'like', '%'.$term.'%');
                });
            })
            ->when($filters['category'] ?? null, function ($query, $name) {
                $query->whereHas('category', fn ($q) => $q->where('name', $name));
            })
            ->when(isset($filters['min_price']), fn ($query) => $query->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($query) => $query->where('price', '<=', $filters['max_price']))
            ->when(($filters['availability'] ?? null) === 'in_stock', function ($query) {
                $query->whereHas('variants', fn ($q) => $q->where('stock', '>', 0)->where('is_active', true));
            });

        $products = match ($filters['sort'] ?? 'newest') {
            'oldest' => $products->oldest(),
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'popular' => $products->orderByDesc('views_count'),
            'rating' => $products->withAvg(['reviews' => fn ($q) => $q->where('is_approved', true)], 'rating')->orderByDesc('reviews_avg_rating'),
            default => $products->latest(),
        };

        return view('pages.catalog.shop', [
            'products' => $products->paginate(12)->withQueryString(),
            'filters' => $filters,
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function collection()
    {
        return view('pages.catalog.collection', [
            'products' => Product::query()
                ->with(['category', 'primaryImage'])
                ->where('is_active', true)
                ->latest()
                ->take(12)
                ->get(),
            'categories' => Category::query()
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function categories()
    {
        return view('categories.index', [
            'categories' => Category::query()
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function category(string $slug)
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('categories.show', [
            'category' => $category,
            'products' => Product::query()
                ->with(['primaryImage'])
                ->where('category_id', $category->id)
                ->where('is_active', true)
                ->latest()
                ->paginate(9),
        ]);
    }

    public function reviews()
    {
        return view('reviews.index', [
            'reviews' => Review::query()
                ->with(['user', 'product'])
                ->where('is_approved', true)
                ->latest()
                ->paginate(9),
        ]);
    }

    public function product(string $slug)
    {
        $product = Product::query()
            ->with([
                'category',
                'images',
                'variants' => fn ($q) => $q->where('is_active', true),
                'reviews' => fn ($q) => $q->where('is_approved', true)->with('user')->latest(),
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->with(['primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('pages.catalog.product-show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
