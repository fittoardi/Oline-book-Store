@extends('layouts.app')

@section('title', 'Shop Books — '.config('storefront.brand.name'))
@section('meta_description', 'Shop Daily Books by genre, price range, and availability.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col gap-5 border-b border-black pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">The bookshelf</p>
                <h1 class="mt-3 text-4xl font-bold uppercase leading-tight text-black sm:text-5xl">Find your next favourite.</h1>
                <p class="mt-3 max-w-xl text-sm text-[#737373]">{{ $products->total() }} books for curious minds.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="arcade-button arcade-button-ghost self-start sm:self-auto uppercase">Browse genres ↗</a>
        </div>

        {{-- Filters & Search Form --}}
        <form method="GET" action="{{ route('products.index') }}" class="mt-8 border-b border-[#e5e7eb] pb-8 space-y-4">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr_auto]">
                <div>
                    <label class="sr-only" for="q">Search books</label>
                    <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="arcade-shell w-full px-3 py-3 text-sm" placeholder="Search title, author...">
                </div>

                <div>
                    <label class="sr-only" for="category">Genre</label>
                    <select id="category" name="category" class="arcade-shell w-full px-3 py-3 text-sm">
                        <option value="">All genres</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" @selected(($filters['category'] ?? '') === $category->name)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <label class="sr-only" for="min_price">Min price</label>
                    <input id="min_price" name="min_price" type="number" min="0" step="10000" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min Rp" class="arcade-shell w-full px-2 py-3 text-xs">
                    <label class="sr-only" for="max_price">Max price</label>
                    <input id="max_price" name="max_price" type="number" min="0" step="10000" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max Rp" class="arcade-shell w-full px-2 py-3 text-xs">
                </div>

                <div>
                    <label class="sr-only" for="sort">Sort by</label>
                    <select id="sort" name="sort" class="arcade-shell w-full px-3 py-3 text-sm">
                        @foreach(['newest' => 'Newest arrivals', 'oldest' => 'Oldest first', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'popular' => 'Most popular', 'rating' => 'Highest rated'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="arcade-button arcade-button-primary uppercase font-bold px-5" type="submit">Filter</button>
                    @if(!empty(array_filter($filters ?? [])))
                        <a href="{{ route('products.index') }}" class="arcade-button arcade-button-ghost uppercase text-xs flex items-center">Reset</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <label class="flex items-center gap-2 cursor-pointer font-bold uppercase text-[#333]">
                    <input type="checkbox" name="availability" value="in_stock" @checked(($filters['availability'] ?? '') === 'in_stock') class="border-black">
                    <span>In stock only</span>
                </label>
            </div>
        </form>

        {{-- Products Grid --}}
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($products as $product)
                @php($inStock = $product->variants->sum(fn ($v) => $v->availableStock()) > 0)
                <article class="arcade-card group flex flex-col justify-between">
                    <div>
                        <a href="{{ route('products.show', $product) }}" class="block">
                            <div class="relative aspect-[4/5] overflow-hidden bg-[#e5e7eb] border border-[#e5e7eb]">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center text-xs uppercase text-[#737373]">No cover</div>
                                @endif

                                <div class="absolute left-3 top-3 flex flex-col gap-1">
                                    @if($product->is_new)
                                        <span class="arcade-tag bg-[#f3e5df]">NEW</span>
                                    @endif
                                    @if($product->compare_price)
                                        <span class="arcade-tag bg-[#faa21f]">SALE</span>
                                    @endif
                                    @if(! $inStock)
                                        <span class="arcade-tag bg-black text-white">OUT OF STOCK</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase text-[#737373] tracking-wider">{{ $product->category?->name }}</p>
                                    <h2 class="mt-1 text-sm font-bold uppercase truncate">{{ $product->name }}</h2>
                                    <p class="mt-1 text-xs text-[#737373]">{{ $product->author }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    @if($product->compare_price)
                                        <del class="text-[11px] text-[#737373]">Rp{{ number_format($product->compare_price, 0, ',', '.') }}</del>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <div class="mt-2 flex items-center justify-between text-xs text-[#737373]">
                            <span class="text-[#faa21f] font-bold">★ {{ round($product->reviews_avg_rating ?? 5.0, 1) }}</span>
                            <span>{{ $inStock ? 'Ready stock' : 'Habis' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2 border-t border-[#e5e7eb] pt-3">
                        <form method="POST" action="{{ route('cart.items.store') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="slug" value="{{ $product->slug }}">
                            <input type="hidden" name="quantity" value="1">
                            <button class="arcade-button arcade-button-primary w-full text-center uppercase text-xs font-bold" type="submit" @disabled(! $inStock)>
                                {{ $inStock ? 'Add to cart' : 'Sold out' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('wishlist.toggle') }}">
                            @csrf
                            <input type="hidden" name="slug" value="{{ $product->slug }}">
                            <button class="arcade-button arcade-button-ghost px-3 text-xs" type="submit" aria-label="Save {{ $product->name }} to wishlist">
                                ♡
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="arcade-card col-span-full py-16 text-center">
                    <p class="text-lg font-bold uppercase">No products match your filters.</p>
                    <p class="mt-2 text-xs text-[#737373]">Try adjusting your search terms or price filter.</p>
                    <a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-5 inline-flex uppercase">Clear filters</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-10">{{ $products->links() }}</div>
    </section>
@endsection
