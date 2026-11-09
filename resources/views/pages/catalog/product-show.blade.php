@extends('layouts.app')

@section('title', $product->name.' — '.config('storefront.brand.name'))
@section('meta_description', $product->description)

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8" x-data="{
        selectedFormat: '{{ $product->variants->firstWhere('stock', '>', 0)?->format ?? $product->variants->first()?->format ?? 'paperback' }}',
        quantity: 1,
        activeTab: 'cover'
    }">
        <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">← Back to bookshelf</a>

        @if(session('status'))
            <div class="mt-4 rounded-[6px] border border-[#faae33] bg-[#faae33] px-5 py-3 text-xs font-bold uppercase tracking-wider text-[#281006]">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 rounded-[6px] border border-[#d1255c] bg-[#281006] p-4 text-xs text-[#faae33]">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.05fr_.95fr]">
            {{-- Image Showcase & Preview --}}
            <div class="tiger-card-deep flex flex-col justify-between p-6 sm:p-10">
                <div class="aspect-[.9] overflow-hidden rounded-[4px] border border-[#6b2e12] bg-[#402011]">
                    <template x-if="activeTab === 'cover'">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" fetchpriority="high" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-xs uppercase text-[#faae33] opacity-60">No cover available</div>
                        @endif
                    </template>
                    <template x-if="activeTab === 'preview'">
                        <div class="flex h-full flex-col justify-center p-8 text-center bg-[#281006]">
                            <span class="text-xs uppercase tracking-[0.25em] text-[#faae33] font-bold">Excerpt / First Page</span>
                            <blockquote class="mt-4 text-sm italic leading-7 text-[#faae33] opacity-90">
                                “{{ Str::limit($product->description, 280) }}”
                            </blockquote>
                            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-[#9f531b]">— {{ $product->author }}</p>
                        </div>
                    </template>
                </div>
                <div class="mt-6 flex justify-center gap-3">
                    <button type="button" @click="activeTab = 'cover'" :class="activeTab === 'cover' ? 'tiger-button-primary' : 'tiger-button-ghost'" class="tiger-button uppercase">Cover</button>
                    <button type="button" @click="activeTab = 'preview'" :class="activeTab === 'preview' ? 'tiger-button-primary' : 'tiger-button-ghost'" class="tiger-button uppercase">Preview</button>
                </div>
            </div>

            {{-- Product Metadata and Ordering --}}
            <div class="flex flex-col justify-center">
                <div class="flex items-center gap-2">
                    <span class="tiger-tag tiger-tag-gold">{{ $product->category?->name }}</span>
                    @if($product->is_new)
                        <span class="tiger-tag bg-[#9f531b] text-[#faae33] border-[#faae33]">NEW</span>
                    @endif
                    @if($product->is_featured)
                        <span class="tiger-tag bg-[#9f531b] text-[#faae33] border-[#faae33]">BESTSELLER</span>
                    @endif
                    @if($product->compare_price)
                        <span class="tiger-tag tiger-tag-alert">SALE</span>
                    @endif
                </div>

                <h1 class="font-display mt-4 text-5xl uppercase leading-[0.92] tracking-tight text-[#faae33] sm:text-6xl">{{ $product->name }}</h1>
                <p class="mt-2 text-xs font-bold uppercase tracking-widest text-[#9f531b]">by {{ $product->author }}</p>

                {{-- Rating Summary --}}
                <div class="mt-3 flex items-center gap-3 text-xs text-[#faae33]">
                    <span class="font-bold">★ {{ $product->averageRating() }}</span>
                    <span class="opacity-75">({{ $product->reviewCount() }} {{ Str::plural('review', $product->reviewCount()) }})</span>
                </div>

                {{-- Price --}}
                <div class="mt-5 flex items-center gap-4">
                    <strong class="font-display text-4xl text-[#faae33]">Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                    @if($product->compare_price)
                        <del class="text-sm text-[#9f531b]">Rp{{ number_format($product->compare_price, 0, ',', '.') }}</del>
                    @endif
                </div>

                {{-- Synopsis --}}
                <p class="mt-6 text-sm leading-7 text-[#faae33] opacity-90">{{ $product->description }}</p>

                {{-- Book Specs --}}
                <div class="mt-5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-[#faae33] opacity-75 border-t border-[#6b2e12] pt-4">
                    @if($product->publisher)<span>Publisher: {{ $product->publisher }}</span>@endif
                    @if($product->page_count)<span>· {{ $product->page_count }} halaman</span>@endif
                    @if($product->isbn)<span>· ISBN: {{ $product->isbn }}</span>@endif
                    <span>· Bahasa: {{ strtoupper($product->language ?? 'ID') }}</span>
                </div>

                {{-- Edition Selection --}}
                <div class="mt-6 border-y border-dotted border-[#faae33] py-5">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">Select Format</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button
                                type="button"
                                @click="selectedFormat = '{{ $variant->format }}'"
                                :class="selectedFormat === '{{ $variant->format }}' ? 'border-[#faae33] bg-[#faae33] text-[#281006] font-bold' : 'border-[#6b2e12] bg-[#402011] text-[#faae33]'"
                                class="rounded-full border px-4 py-2 text-xs uppercase tracking-wider transition disabled:opacity-40"
                                @disabled($variant->availableStock() < 1)
                            >
                                {{ ucfirst(str_replace('_', ' ', $variant->format)) }}
                                @if($variant->availableStock() < 1)
                                    · <span class="text-[#d1255c]">Habis</span>
                                @else
                                    · Rp{{ number_format($variant->price ?? $product->price, 0, ',', '.') }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Purchase Actions --}}
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <form method="POST" action="{{ route('cart.items.store') }}" class="flex flex-1 gap-2">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <input type="hidden" name="format" :value="selectedFormat">
                        <label class="sr-only" for="quantity">Quantity</label>
                        <input id="quantity" name="quantity" type="number" min="1" max="99" x-model="quantity" class="w-16 rounded-full border border-[#6b2e12] bg-[#281006] px-2 py-3 text-center text-sm font-bold text-[#faae33]">
                        <button class="tiger-button tiger-button-primary flex-1" type="submit">Add to cart</button>
                    </form>

                    <form method="POST" action="{{ route('cart.items.store') }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <input type="hidden" name="format" :value="selectedFormat">
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="buy_now" value="1">
                        <button class="tiger-button tiger-button-ghost w-full" type="submit">Buy now ↗</button>
                    </form>

                    <form method="POST" action="{{ route('wishlist.toggle') }}">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <button class="tiger-button tiger-button-ghost !px-4" type="submit" aria-label="Save to wishlist">♡</button>
                    </form>
                </div>

                <p class="mt-4 text-xs text-[#faae33] opacity-75">
                    Free shipping for orders over Rp{{ number_format(config('storefront.shipping.free_threshold'), 0, ',', '.') }} · Carefully packed
                </p>
            </div>
        </div>

        {{-- Reader Reviews Section --}}
        <div class="mt-20 border-t border-dotted border-[#faae33] pt-12">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b border-[#6b2e12] pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">Reader Feedback</p>
                    <h2 class="mt-2 text-3xl font-bold uppercase tracking-tight text-[#faae33] sm:text-4xl">Customer Reviews</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-base font-bold text-[#faae33]">★ {{ $product->averageRating() }}</span>
                    <span class="text-xs text-[#faae33] opacity-75">({{ $product->reviewCount() }} ulasan)</span>
                </div>
            </div>

            {{-- Review Form for Eligible Verified Buyers --}}
            @if($canReview && ! $hasReviewed)
                <div class="mt-8 rounded-[6px] border border-[#6b2e12] bg-[#402011] p-6 sm:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9f531b]">Verified Purchase</p>
                    <h3 class="mt-2 text-2xl font-bold uppercase text-[#faae33]">Leave a Review</h3>
                    <p class="mt-1 text-xs text-[#faae33] opacity-75">Bagikan pengalaman membaca buku ini kepada komunitas Daily Books.</p>

                    <form method="POST" action="{{ route('reviews.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]" for="rating">Rating (1 to 5 Stars)</label>
                            <select id="rating" name="rating" required class="mt-2 w-full max-w-xs">
                                <option value="5">★★★★★ (5 - Sangat Bagus)</option>
                                <option value="4">★★★★☆ (4 - Bagus)</option>
                                <option value="3">★★★☆☆ (3 - Cukup)</option>
                                <option value="2">★★☆☆☆ (2 - Kurang)</option>
                                <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]" for="comment">Your Review</label>
                            <textarea id="comment" name="comment" rows="4" required class="mt-2 w-full" placeholder="Tuliskan kesan atau pandangan Anda tentang buku ini..."></textarea>
                        </div>

                        <button type="submit" class="tiger-button tiger-button-primary">Submit Review</button>
                    </form>
                </div>
            @elseif($hasReviewed)
                <div class="mt-6 rounded-[6px] border border-[#6b2e12] bg-[#402011] p-4 text-xs text-[#faae33]">
                    ✓ Anda sudah mengulas buku ini. Ulasan baru akan ditampilkan setelah dimoderasi oleh tim kami.
                </div>
            @endif

            {{-- Reviews List --}}
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($product->reviews as $review)
                    <article class="tiger-card p-5">
                        <div class="flex items-center justify-between border-b border-[#6b2e12] pb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#faae33]">{{ $review->user->name ?? 'Reader' }}</span>
                            <span class="text-xs text-[#faae33]">{{ str_repeat('★', $review->rating) }}</span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-[#faae33] opacity-90">“{{ $review->comment }}”</p>
                        <p class="mt-4 border-t border-[#6b2e12] pt-2 text-[10px] uppercase tracking-wider text-[#9f531b]">Verified reader · {{ $review->created_at->format('d M Y') }}</p>
                    </article>
                @empty
                    <div class="tiger-card col-span-full py-10 text-center text-xs text-[#faae33] opacity-75">
                        Belum ada ulasan untuk buku ini. Jadilah yang pertama membaca dan mengulas!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Related Products --}}
        <div class="mt-20 border-t border-dotted border-[#faae33] pt-12">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">You may also like</p>
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3">
                @foreach($relatedProducts as $item)
                    <a href="{{ route('products.show', $item) }}" class="tiger-card group">
                        @if($item->primaryImage)
                            <img src="{{ $item->primaryImage->path }}" alt="{{ $item->name }}" width="900" height="1125" loading="lazy" class="aspect-[4/5] w-full rounded-[4px] border border-[#6b2e12] object-cover transition group-hover:scale-[1.02]">
                        @endif
                        <h3 class="mt-3 text-sm font-bold uppercase text-[#faae33]">{{ $item->name }}</h3>
                        <p class="mt-1 text-xs text-[#faae33] opacity-75">{{ $item->author }}</p>
                        <p class="mt-2 text-sm font-bold text-[#faae33]">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
