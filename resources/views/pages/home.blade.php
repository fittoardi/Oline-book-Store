@extends('layouts.app')

@section('title', 'Daily Books — Stories for every state of mind')
@section('meta_description', 'Daily Books curates fiction, nonfiction, business, children, and poetry books for curious minds.')

@section('content')
    {{-- Hero Poster Section --}}
    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_.9fr] lg:px-8 lg:py-20">
        <div class="tiger-card-deep flex min-h-[520px] flex-col justify-between border border-[#6b2e12] p-6 sm:p-10">
            <div class="flex items-start justify-between gap-4">
                <span class="tiger-tag tiger-tag-gold">NEW TITLES / {{ date('Y') }}</span>
                <span class="text-xs font-bold uppercase tracking-widest text-[#faae33]">01 / {{ max(1, $products->count()) }}</span>
            </div>

            <div class="mx-auto w-full max-w-xs border border-[#6b2e12] bg-[#402011] p-3 shadow-none">
                @if($products->first() && $products->first()->primaryImage)
                    <img src="{{ $products->first()->primaryImage->path }}" alt="{{ $products->first()->name }}" width="900" height="1125" fetchpriority="high" class="aspect-[4/5] w-full object-cover">
                @endif
            </div>

            <div class="flex items-center justify-between text-xs font-bold tracking-widest text-[#faae33]">
                <span>THE READING EDIT</span>
                <span>•</span>
                <span>CURATED TITLES</span>
            </div>
        </div>

        <div class="flex flex-col justify-center py-4 lg:pl-8">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">{{ config('storefront.brand.name') }} • Independent Bookstore</p>
            <h1 class="mt-4 text-6xl font-bold uppercase leading-[0.88] tracking-tight text-[#faae33] sm:text-7xl lg:text-8xl">
                Find a story that stays.
            </h1>
            <p class="mt-6 max-w-lg text-sm leading-7 text-[#faae33] opacity-90">
                Buku-buku terpilih untuk hari yang penuh pertanyaan, perjalanan yang pelan, dan imajinasi yang tidak mau diam.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary">Browse the shelf ↗</a>
                <a href="{{ route('about') }}" class="tiger-button tiger-button-ghost">Our story</a>
            </div>

            <div class="mt-12 grid grid-cols-3 border-y border-dotted border-[#faae33] py-5 text-xs text-[#faae33]">
                <div>
                    <strong class="font-display block text-2xl tracking-normal text-[#faae33]">{{ config('storefront.home.stats.reader_rating') }}</strong>
                    <span class="uppercase tracking-wider opacity-80">reader rating</span>
                </div>
                <div>
                    <strong class="font-display block text-2xl tracking-normal text-[#faae33]">{{ $products->count() }}+</strong>
                    <span class="uppercase tracking-wider opacity-80">titles on shelf</span>
                </div>
                <div>
                    <strong class="font-display block text-2xl tracking-normal text-[#faae33]">{{ config('storefront.home.stats.carefully_packed') }}%</strong>
                    <span class="uppercase tracking-wider opacity-80">carefully packed</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Shop by Genre Section --}}
    <section class="border-t border-dotted border-[#faae33]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between border-b border-[#6b2e12] pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">Browse the shelf</p>
                    <h2 class="mt-2 text-3xl font-bold uppercase tracking-tight text-[#faae33] sm:text-5xl">Shop by genre</h2>
                </div>
                <a href="{{ route('categories.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">All genres ↗</a>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="tiger-card group flex min-h-48 flex-col justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#9f531b]">0{{ $loop->iteration }}</span>
                        <div>
                            <h3 class="text-2xl font-bold uppercase text-[#faae33] group-hover:underline">{{ $category->name }}</h3>
                            <p class="mt-2 text-xs leading-5 text-[#faae33] opacity-80">{{ $category->description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest Reads Section --}}
    <section class="border-t border-dotted border-[#faae33]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between border-b border-[#6b2e12] pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">New Releases</p>
                    <h2 class="mt-2 text-3xl font-bold uppercase tracking-tight text-[#faae33] sm:text-5xl">Latest reads</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">Shop all ↗</a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($products as $product)
                    <article class="tiger-card flex flex-col justify-between">
                        <a href="{{ route('products.show', $product) }}" class="block">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-[4px] border border-[#6b2e12] bg-[#281006]">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @endif
                                <div class="absolute left-3 top-3 flex flex-col gap-1.5">
                                    @if($product->is_new)
                                        <span class="tiger-tag tiger-tag-gold !text-[10px]">NEW</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="tiger-tag bg-[#9f531b] text-[#faae33] border-[#faae33] !text-[10px]">BESTSELLER</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4 flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#9f531b]">{{ $product->category?->name }}</p>
                                    <h3 class="mt-1 text-base font-bold uppercase text-[#faae33]">{{ $product->name }}</h3>
                                    <p class="mt-1 text-xs text-[#faae33] opacity-75">{{ $product->author }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-bold text-[#faae33]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                        <a href="{{ route('products.show', $product) }}" class="tiger-button tiger-button-primary mt-5 w-full text-center">Add to cart</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Brand Belief Quote Section --}}
    <section class="border-t border-dotted border-[#faae33]">
        <div class="mx-auto max-w-7xl px-4 py-20 text-center sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">The {{ config('storefront.brand.name') }} Manifesto</p>
            <blockquote class="font-display mx-auto mt-6 max-w-4xl text-4xl uppercase leading-[0.92] tracking-tight text-[#faae33] sm:text-6xl lg:text-7xl">
                A Good Book Makes Room For A Bigger World.
            </blockquote>
            <p class="mx-auto mt-6 max-w-lg text-sm text-[#faae33] opacity-85">
                Buku bukan sekadar kertas dan cetakan, melainkan api yang menyalakan imajinasi dan memperluas sudut pandang.
            </p>
            <a href="{{ route('about') }}" class="tiger-button tiger-button-ghost mt-8">Read our story ↗</a>
        </div>
    </section>

    {{-- Reader Reviews Section --}}
    <section class="border-t border-dotted border-[#faae33]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between border-b border-[#6b2e12] pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">Reader notes</p>
                    <h2 class="mt-2 text-3xl font-bold uppercase tracking-tight text-[#faae33] sm:text-5xl">Read and loved</h2>
                </div>
                <a href="{{ route('reviews.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">Read all ↗</a>
            </div>

            <div class="mt-8 grid gap-4 lg:grid-cols-3">
                @forelse($reviews as $review)
                    <article class="tiger-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-3 border-b border-[#6b2e12] pb-3">
                                <strong class="text-xs font-bold uppercase tracking-wider text-[#faae33]">{{ $review->user->name ?? 'Reader' }}</strong>
                                <span class="text-xs text-[#faae33]">{{ str_repeat('★', $review->rating) }}</span>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-[#faae33] opacity-90">“{{ $review->comment }}”</p>
                        </div>
                        <p class="mt-6 text-[10px] font-bold uppercase tracking-wider text-[#9f531b]">{{ $review->product->name ?? '' }}</p>
                    </article>
                @empty
                    <div class="tiger-card col-span-full py-12 text-center text-xs text-[#faae33] opacity-75">
                        Belum ada ulasan yang disetujui.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
