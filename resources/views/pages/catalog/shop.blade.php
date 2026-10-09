@extends('layouts.app')

@section('title', 'Shop — Kembang Books')
@section('meta_description', 'Shop Kembang Books by genre, price, and availability.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-5 border-b border-black pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">The bookshelf</p>
                <h1 class="mt-3 text-4xl font-bold uppercase leading-tight text-black sm:text-5xl">Find your next favourite.</h1>
                <p class="mt-3 max-w-xl text-sm text-[#737373]">{{ $products->count() }} books for curious minds.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="arcade-button arcade-button-ghost self-start sm:self-auto">Browse genres</a>
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="mt-8 grid gap-3 border-b border-[#e5e7eb] pb-8 md:grid-cols-[1.5fr_1fr_1fr_auto]">
            <label class="sr-only" for="q">Search products</label>
            <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="arcade-shell px-3 py-3 text-sm" placeholder="Search products...">
            <label class="sr-only" for="category">Category</label>
            <select id="category" name="category" class="arcade-shell px-3 py-3 text-sm">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category['name'] }}" @selected(($filters['category'] ?? '') === $category['name'])>{{ $category['name'] }}</option>
                @endforeach
            </select>
            <label class="sr-only" for="sort">Sort products</label>
            <select id="sort" name="sort" class="arcade-shell px-3 py-3 text-sm">
                @foreach(['newest' => 'Newest', 'oldest' => 'Oldest', 'price_asc' => 'Price low to high', 'price_desc' => 'Price high to low', 'rating' => 'Highest rated'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="arcade-button arcade-button-primary uppercase" type="submit">Apply</button>
            <label class="flex items-center gap-2 text-sm md:col-span-4"><input type="checkbox" name="availability" value="in_stock" @checked(($filters['availability'] ?? '') === 'in_stock')> In stock only</label>
        </form>

        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($products as $product)
                <article class="arcade-card group">
                    <a href="{{ route('products.show', $product['slug']) }}" class="block">
                        <div class="relative aspect-[4/5] overflow-hidden bg-[#e5e7eb]">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" width="900" height="1125" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @if($product['tag'])<span class="arcade-tag absolute left-3 top-3 bg-[#f3e5df]">{{ strtoupper($product['tag']) }}</span>@endif
                        </div>
                        <div class="mt-3 flex items-start justify-between gap-3">
                            <div><p class="text-[10px] uppercase text-[#737373]">{{ $product['category'] }}</p><h2 class="mt-1 text-sm font-bold uppercase">{{ $product['name'] }}</h2></div>
                            <p class="shrink-0 text-sm">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                        </div>
                    </a>
                    <div class="mt-4 grid gap-2">
                        <a href="{{ route('products.show', $product['slug']) }}" class="arcade-button arcade-button-ghost text-center uppercase">Try in <span class="arcade-tag">AR</span></a>
                        <a href="{{ route('products.show', $product['slug']) }}" class="arcade-button arcade-button-primary text-center uppercase">Add to cart</a>
                    </div>
                </article>
            @empty
                <div class="arcade-card col-span-full py-16 text-center">
                    <p class="text-lg font-bold uppercase">No products match your filters.</p>
                    <a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-5 inline-flex uppercase">Clear filters</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
