@extends('layouts.app')

@section('title', 'Daily Books — Stories for every state of mind')
@section('meta_description', 'Daily Books curates fiction, nonfiction, business, children, and poetry books for curious minds.')

@section('content')
    @php
        $slideItems = collect();
        if ($products->isNotEmpty()) {
            $firstProduct = $products->first();
            $secondProduct = $products->skip(1)->first() ?? $firstProduct;
            $thirdProduct = $products->skip(2)->first() ?? $firstProduct;

            $slideItems->push([
                'tag' => 'NEW TITLES / '.date('Y'),
                'badge' => 'THE READING EDIT',
                'eyebrow' => config('storefront.brand.name').' • Independent Bookstore',
                'title' => 'Find a story that stays.',
                'subtitle' => 'Buku-buku terpilih untuk hari yang penuh pertanyaan, perjalanan yang pelan, dan imajinasi yang tidak mau diam.',
                'product' => $firstProduct,
                'cta_primary_text' => 'Browse the shelf ↗',
                'cta_primary_link' => route('products.index'),
                'cta_secondary_text' => 'Explore title',
                'cta_secondary_link' => route('products.show', $firstProduct),
            ]);

            $slideItems->push([
                'tag' => 'CURATED EDIT / 2026',
                'badge' => 'BESTSELLER SPOTLIGHT',
                'eyebrow' => 'Handpicked Literature • Daily Books',
                'title' => 'Stories That Burn In Memory.',
                'subtitle' => 'Koleksi karya sastra kontemporer, esai mendalam, dan tulisan yang membuka perspektif baru bagi pembaca.',
                'product' => $secondProduct,
                'cta_primary_text' => 'Shop All Books ↗',
                'cta_primary_link' => route('products.index'),
                'cta_secondary_text' => 'View Title',
                'cta_secondary_link' => route('products.show', $secondProduct),
            ]);

            $slideItems->push([
                'tag' => 'READER ESSENTIALS',
                'badge' => 'GENUINE COPIES ONLY',
                'eyebrow' => 'Thoughtful Reading • Free Shipping Rp300k',
                'title' => 'A Good Book Makes Room For A Bigger World.',
                'subtitle' => 'Buku bukan sekadar kertas dan cetakan, melainkan api yang menyalakan imajinasi dan memperluas sudut pandang.',
                'product' => $thirdProduct,
                'cta_primary_text' => 'Browse Genres ↗',
                'cta_primary_link' => route('categories.index'),
                'cta_secondary_text' => 'Read Our Story',
                'cta_secondary_link' => route('about'),
            ]);
        }
    @endphp

    {{-- Full-Width Hero Carousel --}}
    @if($slideItems->isNotEmpty())
        <section 
            x-data="{
                currentSlide: 0,
                totalSlides: {{ $slideItems->count() }},
                autoplayTimer: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    this.stopAutoplay();
                    this.autoplayTimer = setInterval(() => {
                        this.next();
                    }, 6500);
                },
                stopAutoplay() {
                    if (this.autoplayTimer) clearInterval(this.autoplayTimer);
                },
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },
                goTo(index) {
                    this.currentSlide = index;
                    this.startAutoplay();
                }
            }"
            @mouseenter="stopAutoplay()"
            @mouseleave="startAutoplay()"
            @keydown.right.window="next()"
            @keydown.left.window="prev()"
            class="relative w-full overflow-hidden bg-[#823513] border-b border-dotted border-[#faae33]"
            aria-label="Featured Books Carousel"
        >
            {{-- Carousel Background Pattern --}}
            <div class="pointer-events-none absolute inset-0 opacity-15" style="background-image: radial-gradient(#faae33 1px, transparent 1px); background-size: 28px 28px;"></div>

            {{-- Carousel Slides Container --}}
            <div class="relative w-full">
                @foreach($slideItems as $index => $slide)
                    <div 
                        x-show="currentSlide === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700 transform"
                        x-transition:enter-start="opacity-0 translate-x-4 scale-[0.99]"
                        x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                        x-transition:leave="transition ease-in duration-300 transform absolute inset-0"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4"
                        x-cloak
                        class="w-full"
                    >
                        <div class="mx-auto max-w-[1440px] px-4 py-12 sm:px-8 sm:py-16 lg:px-16 lg:py-24">
                            <div class="grid items-center gap-10 lg:grid-cols-[1.15fr_0.85fr] xl:gap-16">
                                {{-- Left Column: Typographic Poster --}}
                                <div class="flex flex-col justify-center">
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <span class="tiger-tag tiger-tag-gold">{{ $slide['tag'] }}</span>
                                        <span class="tiger-tag">{{ $slide['badge'] }}</span>
                                        <span class="text-xs font-mono font-bold tracking-widest text-[#faae33]/70 sm:ml-auto">
                                            0{{ $index + 1 }} / 0{{ $slideItems->count() }}
                                        </span>
                                    </div>

                                    <p class="mt-4 text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]/80">
                                        {{ $slide['eyebrow'] }}
                                    </p>

                                    <h1 class="mt-3 font-['Bebas_Neue',sans-serif] text-5xl font-bold uppercase leading-[0.88] tracking-tight text-[#faae33] sm:text-7xl lg:text-8xl xl:text-9xl">
                                        {{ $slide['title'] }}
                                    </h1>

                                    <p class="mt-6 max-w-xl text-sm sm:text-base leading-7 text-[#faae33]/90">
                                        {{ $slide['subtitle'] }}
                                    </p>

                                    @if($slide['product'])
                                        <div class="mt-6 flex items-center gap-3 text-xs text-[#faae33]">
                                            <span class="rounded-full border border-[#6b2e12] bg-[#281006] px-3.5 py-1.5 font-bold uppercase tracking-wider text-[#faae33]">
                                                Rp{{ number_format($slide['product']->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[#faae33]/70">·</span>
                                            <span class="font-medium text-[#faae33]/80">By <strong class="text-[#faae33]">{{ $slide['product']->author }}</strong></span>
                                        </div>
                                    @endif

                                    <div class="mt-8 flex flex-wrap items-center gap-4">
                                        <a href="{{ $slide['cta_primary_link'] }}" class="tiger-button tiger-button-primary px-7 py-3.5 text-xs font-bold tracking-wider">
                                            {{ $slide['cta_primary_text'] }}
                                        </a>
                                        <a href="{{ $slide['cta_secondary_link'] }}" class="tiger-button tiger-button-ghost px-6 py-3.5 text-xs font-bold tracking-wider">
                                            {{ $slide['cta_secondary_text'] }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Right Column: Showcase Book Frame --}}
                                <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                                    <div class="tiger-card-deep relative flex min-h-[460px] flex-col justify-between border border-[#6b2e12] p-6 sm:p-8">
                                        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-widest text-[#faae33]/70 font-mono">
                                            <span>THE READING EDIT</span>
                                            <span>{{ $slide['product']->category?->name ?? 'CURATED' }}</span>
                                        </div>

                                        <div class="my-6 mx-auto w-full max-w-xs rounded-[4px] border border-[#6b2e12] bg-[#402011] p-3 shadow-none group">
                                            <a href="{{ route('products.show', $slide['product']) }}" class="block overflow-hidden rounded-[2px]">
                                                @if($slide['product']->primaryImage)
                                                    <img 
                                                        src="{{ $slide['product']->primaryImage->path }}" 
                                                        alt="{{ $slide['product']->name }}" 
                                                        width="900" 
                                                        height="1125" 
                                                        fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}" 
                                                        class="aspect-[4/5] w-full object-cover transition-transform duration-500 hover:scale-105"
                                                    >
                                                @else
                                                    <div class="flex aspect-[4/5] w-full items-center justify-center bg-[#281006] text-xs font-bold uppercase tracking-widest text-[#faae33]/40">
                                                        No Cover
                                                    </div>
                                                @endif
                                            </a>
                                        </div>

                                        <div class="flex items-end justify-between gap-4 border-t border-dotted border-[#faae33]/30 pt-4">
                                            <div>
                                                <a href="{{ route('products.show', $slide['product']) }}" class="block text-sm font-bold uppercase text-[#faae33] hover:underline line-clamp-1">
                                                    {{ $slide['product']->name }}
                                                </a>
                                                <p class="text-xs text-[#faae33]/70 italic">{{ $slide['product']->author }}</p>
                                            </div>
                                            <a href="{{ route('products.show', $slide['product']) }}" class="tiger-button tiger-button-primary !px-3 !py-1 text-xs shrink-0">
                                                Add ↗
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Floating Controls: Prev & Next Buttons --}}
            <div class="pointer-events-none absolute inset-y-0 left-0 right-0 flex items-center justify-between px-2 sm:px-6">
                <button 
                    type="button" 
                    @click="prev()" 
                    class="pointer-events-auto flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full border border-[#faae33] bg-[#281006]/80 text-lg font-bold text-[#faae33] backdrop-blur-sm transition-all hover:bg-[#faae33] hover:text-[#281006] focus:outline-none"
                    aria-label="Previous Slide"
                >
                    ←
                </button>
                <button 
                    type="button" 
                    @click="next()" 
                    class="pointer-events-auto flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full border border-[#faae33] bg-[#281006]/80 text-lg font-bold text-[#faae33] backdrop-blur-sm transition-all hover:bg-[#faae33] hover:text-[#281006] focus:outline-none"
                    aria-label="Next Slide"
                >
                    →
                </button>
            </div>

            {{-- Slide Indicator Pills --}}
            <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2">
                @foreach($slideItems as $index => $slide)
                    <button 
                        type="button"
                        @click="goTo({{ $index }})"
                        :class="currentSlide === {{ $index }} ? 'w-8 bg-[#faae33]' : 'w-2 bg-[#6b2e12] hover:bg-[#faae33]/60'"
                        class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                        aria-label="Go to slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Full-Width Metrics Strip --}}
    <section class="border-b border-dotted border-[#faae33] bg-[#281006]">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 divide-x divide-[#6b2e12] text-center text-xs text-[#faae33]">
                <div class="px-2">
                    <strong class="font-['Bebas_Neue',sans-serif] block text-2xl sm:text-3xl tracking-normal text-[#faae33] leading-none">
                        {{ config('storefront.home.stats.reader_rating') }} ★
                    </strong>
                    <span class="mt-1 block text-[11px] uppercase tracking-wider opacity-80">reader rating</span>
                </div>
                <div class="px-2">
                    <strong class="font-['Bebas_Neue',sans-serif] block text-2xl sm:text-3xl tracking-normal text-[#faae33] leading-none">
                        {{ $products->count() }}+
                    </strong>
                    <span class="mt-1 block text-[11px] uppercase tracking-wider opacity-80">titles on shelf</span>
                </div>
                <div class="px-2">
                    <strong class="font-['Bebas_Neue',sans-serif] block text-2xl sm:text-3xl tracking-normal text-[#faae33] leading-none">
                        {{ config('storefront.home.stats.carefully_packed') }}%
                    </strong>
                    <span class="mt-1 block text-[11px] uppercase tracking-wider opacity-80">carefully packed</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Shop by Genre Section --}}
    <section class="border-b border-dotted border-[#faae33]">
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
    <section class="border-b border-dotted border-[#faae33]">
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
    <section class="border-b border-dotted border-[#faae33]">
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
    <section class="border-b border-dotted border-[#faae33]">
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
