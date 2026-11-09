@extends('layouts.app')

@section('title', 'Shop Books — '.config('storefront.brand.name'))
@section('meta_description', 'Shop Daily Books by genre, price range, and availability.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col gap-5 border-b border-dotted border-[#faae33] pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">The Bookshelf</p>
                <h1 class="font-display mt-3 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-7xl">
                    Find Your Next Favourite.
                </h1>
                <p class="mt-3 max-w-xl text-xs uppercase tracking-wider text-[#faae33] opacity-80">{{ $products->total() }} curated titles for curious minds.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="tiger-button tiger-button-ghost self-start sm:self-auto">Browse genres ↗</a>
        </div>

        {{-- Filters & Search Form --}}
        <form method="GET" action="{{ route('products.index') }}" class="mt-8 rounded-[6px] border border-[#6b2e12] bg-[#402011] p-5 space-y-4">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr_auto]">
                <div>
                    <label class="sr-only" for="q">Search books</label>
                    <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="w-full" placeholder="Search title, author...">
                </div>

                <div>
                    <label class="sr-only" for="category">Genre</label>
                    <select id="category" name="category" class="w-full">
                        <option value="">All genres</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" @selected(($filters['category'] ?? '') === $category->name)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <label class="sr-only" for="min_price">Min price</label>
                    <input id="min_price" name="min_price" type="number" min="0" step="10000" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min Rp" class="w-full !px-3 !text-xs">
                    <label class="sr-only" for="max_price">Max price</label>
                    <input id="max_price" name="max_price" type="number" min="0" step="10000" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max Rp" class="w-full !px-3 !text-xs">
                </div>

                <div>
                    <label class="sr-only" for="sort">Sort by</label>
                    <select id="sort" name="sort" class="w-full">
                        @foreach(['newest' => 'Newest arrivals', 'oldest' => 'Oldest first', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'popular' => 'Most popular', 'rating' => 'Highest rated'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="tiger-button tiger-button-primary px-6" type="submit">Filter</button>
                    @if(!empty(array_filter($filters ?? [])))
                        <a href="{{ route('products.index') }}" class="tiger-button tiger-button-ghost text-xs">Reset</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <label class="flex items-center gap-2 cursor-pointer font-bold uppercase text-[#faae33]">
                    <input type="checkbox" name="availability" value="in_stock" @checked(($filters['availability'] ?? '') === 'in_stock') class="accent-[#faae33]">
                    <span>In stock only</span>
                </label>
            </div>
        </form>

        {{-- Products Grid --}}
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($products as $product)
                @php($inStock = $product->variants->sum(fn ($v) => $v->availableStock()) > 0)
                <article class="tiger-card group flex flex-col justify-between">
                    <div>
                        <a href="{{ route('products.show', $product) }}" class="block">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-[4px] border border-[#6b2e12] bg-[#281006]">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center text-xs uppercase text-[#faae33] opacity-60">No cover</div>
                                @endif

                                <div class="absolute left-3 top-3 flex flex-col gap-1.5">
                                    @if($product->is_new)
                                        <span class="tiger-tag tiger-tag-gold !text-[10px]">NEW</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="tiger-tag bg-[#9f531b] text-[#faae33] border-[#faae33] !text-[10px]">BESTSELLER</span>
                                    @endif
                                    @if($product->compare_price)
                                        <span class="tiger-tag tiger-tag-alert !text-[10px]">SALE</span>
                                    @endif
                                    @if(! $inStock)
                                        <span class="tiger-tag bg-[#281006] text-[#faae33] border-[#6b2e12] !text-[10px]">OUT OF STOCK</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase tracking-wider text-[#9f531b] font-bold">{{ $product->category?->name }}</p>
                                    <h2 class="mt-1 text-sm font-bold uppercase truncate text-[#faae33]">{{ $product->name }}</h2>
                                    <p class="mt-1 text-xs text-[#faae33] opacity-75 truncate">{{ $product->author }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-bold text-[#faae33]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    @if($product->compare_price)
                                        <del class="text-[11px] text-[#9f531b] block">Rp{{ number_format($product->compare_price, 0, ',', '.') }}</del>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <div class="mt-3 flex items-center justify-between text-xs text-[#faae33]">
                            <span class="font-bold">★ {{ round($product->reviews_avg_rating ?? 5.0, 1) }}</span>
                            <span class="text-[11px] opacity-75">{{ $inStock ? 'Ready stock' : 'Habis' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2 border-t border-[#6b2e12] pt-3">
                        <form method="POST" action="{{ route('cart.items.store') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="slug" value="{{ $product->slug }}">
                            <input type="hidden" name="quantity" value="1">
                            <button class="tiger-button tiger-button-primary w-full text-center text-xs font-bold" type="submit" @disabled(! $inStock)>
                                {{ $inStock ? 'Add to cart' : 'Sold out' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('wishlist.toggle') }}">
                            @csrf
                            <input type="hidden" name="slug" value="{{ $product->slug }}">
                            <button class="tiger-button tiger-button-ghost !px-3 text-xs" type="submit" aria-label="Save {{ $product->name }} to wishlist">
                                ♡
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="tiger-card col-span-full py-16 text-center">
                    <p class="font-display text-3xl uppercase text-[#faae33]">No products match your filters.</p>
                    <p class="mt-2 text-xs text-[#faae33] opacity-75">Try adjusting your search terms or price filter.</p>
                    <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary mt-6">Clear filters</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-10">{{ $products->links() }}</div>
    </section>
@endsection
