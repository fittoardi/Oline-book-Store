@extends('layouts.app')

@section('title', $product->name.' — '.config('storefront.brand.name'))
@section('meta_description', $product->description)

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8" x-data="{
        selectedFormat: '{{ $product->variants->firstWhere('stock', '>', 0)?->format ?? $product->variants->first()?->format ?? 'paperback' }}',
        quantity: 1,
        activeTab: 'cover'
    }">
        <a href="{{ route('products.index') }}" class="text-sm font-bold uppercase hover:underline">← Back to bookshelf</a>

        @if(session('status'))
            <div class="mt-4 border border-black bg-[#faa21f] px-4 py-3 text-sm font-bold text-black">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 border border-black bg-red-100 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.05fr_.95fr]">
            {{-- Image Showcase & Preview --}}
            <div class="arcade-checkerboard p-6 sm:p-10">
                <div class="aspect-[.9] overflow-hidden border border-black bg-[#f3e5df]">
                    <template x-if="activeTab === 'cover'">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" fetchpriority="high" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-sm uppercase text-[#737373]">No cover available</div>
                        @endif
                    </template>
                    <template x-if="activeTab === 'preview'">
                        <div class="flex h-full flex-col justify-center p-6 text-center">
                            <span class="text-xs uppercase tracking-[.2em] text-[#faa21f]">Excerpt / First Page</span>
                            <blockquote class="mt-4 text-sm italic leading-7 text-[#333333]">
                                “{{ Str::limit($product->description, 280) }}”
                            </blockquote>
                            <p class="mt-4 text-xs font-bold uppercase">— {{ $product->author }}</p>
                        </div>
                    </template>
                </div>
                <div class="mt-5 flex justify-center gap-2">
                    <button type="button" @click="activeTab = 'cover'" :class="activeTab === 'cover' ? 'arcade-button-primary' : 'arcade-button-ghost'" class="arcade-button uppercase">Cover</button>
                    <button type="button" @click="activeTab = 'preview'" :class="activeTab === 'preview' ? 'arcade-button-primary' : 'arcade-button-ghost'" class="arcade-button uppercase">Preview</button>
                </div>
            </div>

            {{-- Product Metadata and Ordering --}}
            <div class="flex flex-col justify-center">
                <div class="flex items-center gap-2">
                    <span class="arcade-tag bg-[#f3e5df] uppercase">{{ $product->category?->name }}</span>
                    @if($product->is_new)
                        <span class="arcade-tag bg-[#f3e5df]">NEW</span>
                    @endif
                    @if($product->compare_price)
                        <span class="arcade-tag bg-[#faa21f]">SALE</span>
                    @endif
                </div>

                <h1 class="mt-4 text-4xl font-bold uppercase leading-tight sm:text-5xl">{{ $product->name }}</h1>
                <p class="mt-2 text-sm text-[#737373] uppercase tracking-wider">by {{ $product->author }}</p>

                {{-- Rating Summary --}}
                <div class="mt-3 flex items-center gap-3 text-sm">
                    <span class="font-bold text-[#faa21f]">★ {{ $product->averageRating() }}</span>
                    <span class="text-[#737373]">({{ $product->reviewCount() }} {{ Str::plural('review', $product->reviewCount()) }})</span>
                </div>

                {{-- Price --}}
                <div class="mt-5 flex items-center gap-3">
                    <strong class="text-3xl font-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                    @if($product->compare_price)
                        <del class="text-base text-[#737373]">Rp{{ number_format($product->compare_price, 0, ',', '.') }}</del>
                    @endif
                </div>

                {{-- Synopsis --}}
                <p class="mt-6 text-sm leading-7 text-[#333333]">{{ $product->description }}</p>

                {{-- Book Specs --}}
                <div class="mt-5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-[#737373] border-t border-[#e5e7eb] pt-4">
                    @if($product->publisher)<span>Publisher: {{ $product->publisher }}</span>@endif
                    @if($product->page_count)<span>· {{ $product->page_count }} halaman</span>@endif
                    @if($product->isbn)<span>· ISBN: {{ $product->isbn }}</span>@endif
                    <span>· Bahasa: {{ strtoupper($product->language ?? 'ID') }}</span>
                </div>

                {{-- Edition Selection --}}
                <div class="mt-6 border-y border-black py-5">
                    <p class="text-xs font-bold uppercase tracking-[.15em] text-[#737373]">Select Format</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button
                                type="button"
                                @click="selectedFormat = '{{ $variant->format }}'"
                                :class="selectedFormat === '{{ $variant->format }}' ? 'border-black bg-[#faa21f] text-black font-bold' : 'border-[#e5e7eb] bg-[#f3e5df] text-[#333]'"
                                class="border px-3 py-2 text-xs uppercase transition disabled:opacity-50"
                                @disabled($variant->availableStock() < 1)
                            >
                                {{ ucfirst(str_replace('_', ' ', $variant->format)) }}
                                @if($variant->availableStock() < 1)
                                    · <span class="text-red-600">Habis</span>
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
                        <input id="quantity" name="quantity" type="number" min="1" max="99" x-model="quantity" class="w-16 border border-black bg-[#f3e5df] px-2 py-3 text-center text-sm font-bold">
                        <button class="arcade-button arcade-button-primary flex-1 uppercase" type="submit">Add to cart</button>
                    </form>

                    <form method="POST" action="{{ route('cart.items.store') }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <input type="hidden" name="format" :value="selectedFormat">
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="buy_now" value="1">
                        <button class="arcade-button arcade-button-ghost w-full uppercase font-bold" type="submit">Buy now ↗</button>
                    </form>

                    <form method="POST" action="{{ route('wishlist.toggle') }}">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <button class="arcade-button arcade-button-ghost px-4 py-3" type="submit" aria-label="Save to wishlist">♡</button>
                    </form>
                </div>

                <p class="mt-4 text-xs text-[#737373]">
                    Free shipping for orders over Rp{{ number_format(config('storefront.shipping.free_threshold'), 0, ',', '.') }} · Carefully packed
                </p>
            </div>
        </div>

        {{-- Reader Reviews Section --}}
        <div class="mt-16 border-t border-black pt-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b border-black pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Reader Feedback</p>
                    <h2 class="mt-2 text-2xl font-bold uppercase">Customer Reviews</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-bold text-[#faa21f]">★ {{ $product->averageRating() }}</span>
                    <span class="text-sm text-[#737373]">({{ $product->reviewCount() }} ulasan)</span>
                </div>
            </div>

            {{-- Review Form for Eligible Verified Buyers --}}
            @if($canReview && ! $hasReviewed)
                <div class="mt-8 border border-black bg-[#f3e5df] p-6 sm:p-8">
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Verified Purchase</p>
                    <h3 class="mt-2 text-lg font-bold uppercase">Leave a Review</h3>
                    <p class="mt-1 text-xs text-[#737373]">Bagikan pengalaman membaca buku ini kepada komunitas Kembang Books.</p>

                    <form method="POST" action="{{ route('reviews.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">

                        <div>
                            <label class="block text-xs font-bold uppercase" for="rating">Rating (1 to 5 Stars)</label>
                            <select id="rating" name="rating" required class="mt-2 w-full max-w-xs border border-black bg-[#f3e5df] px-3 py-2 text-sm">
                                <option value="5">★★★★★ (5 - Sangat Bagus)</option>
                                <option value="4">★★★★☆ (4 - Bagus)</option>
                                <option value="3">★★★☆☆ (3 - Cukup)</option>
                                <option value="2">★★☆☆☆ (2 - Kurang)</option>
                                <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase" for="comment">Your Review</label>
                            <textarea id="comment" name="comment" rows="4" required class="mt-2 w-full resize-y border border-black bg-[#f3e5df] p-3 text-sm" placeholder="Tuliskan kesan atau pandangan Anda tentang buku ini..."></textarea>
                        </div>

                        <button type="submit" class="arcade-button arcade-button-primary uppercase">Submit Review</button>
                    </form>
                </div>
            @elseif($hasReviewed)
                <div class="mt-6 border border-black bg-[#f3e5df] p-4 text-xs text-[#737373]">
                    ✓ Anda sudah mengulas buku ini. Ulasan baru akan ditampilkan setelah dimoderasi oleh tim kami.
                </div>
            @endif

            {{-- Reviews List --}}
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($product->reviews as $review)
                    <article class="arcade-card p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold uppercase">{{ $review->user->name ?? 'Reader' }}</span>
                            <span class="text-sm text-[#faa21f]">{{ str_repeat('★', $review->rating) }}</span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-[#333333]">“{{ $review->comment }}”</p>
                        <p class="mt-4 border-t border-[#e5e7eb] pt-2 text-[10px] uppercase text-[#737373]">Verified reader · {{ $review->created_at->format('d M Y') }}</p>
                    </article>
                @empty
                    <div class="arcade-card col-span-full py-10 text-center text-sm text-[#737373]">
                        Belum ada ulasan untuk buku ini. Jadilah yang pertama membaca dan mengulas!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Related Products --}}
        <div class="mt-16 border-t border-black pt-10">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">You may also like</p>
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3">
                @foreach($relatedProducts as $item)
                    <a href="{{ route('products.show', $item) }}" class="arcade-card group">
                        @if($item->primaryImage)
                            <img src="{{ $item->primaryImage->path }}" alt="{{ $item->name }}" width="900" height="1125" loading="lazy" class="aspect-[4/5] w-full object-cover transition group-hover:scale-[1.02]">
                        @endif
                        <h3 class="mt-3 text-sm font-bold uppercase">{{ $item->name }}</h3>
                        <p class="mt-1 text-xs text-[#737373]">{{ $item->author }}</p>
                        <p class="mt-2 text-sm font-bold">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
