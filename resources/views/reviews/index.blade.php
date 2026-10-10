@extends('layouts.app')

@section('title', 'Reviews — '.config('storefront.brand.name'))
@section('meta_description', 'Read what the Kembang Books community says about their latest reads.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[.3em] text-[#faa21f]">From the community</p>
                <h1 class="mt-4 text-5xl font-bold uppercase leading-[1.05] sm:text-6xl">Read and loved.</h1>
                <p class="mt-6 max-w-xl leading-8 text-[#333333]">Cerita kecil dari orang-orang yang menjadikan Kembang bagian dari rutinitas mereka.</p>
            </div>
            <div class="arcade-card px-5 py-4 text-right"><div class="text-2xl font-bold text-black">4.8 <span class="text-[#faa21f]">★</span></div><p class="mt-1 text-xs text-[#737373]">Loved by our community</p></div>
        </div>

        <div class="mt-14 grid gap-5 lg:grid-cols-3">
            @forelse($reviews as $review)
                <article class="arcade-card p-7 transition hover:border-black">
                    <div class="flex items-center justify-between"><div><h2 class="text-sm font-bold uppercase text-black">{{ $review->user->name ?? 'Reader' }}</h2><p class="text-xs text-[#737373]">Verified customer</p></div><span class="text-sm tracking-[.15em] text-[#faa21f]">{{ str_repeat('★', $review->rating) }}</span></div>
                    <p class="mt-8 text-lg leading-8 text-[#333333]">“{{ $review->comment }}”</p>
                    <p class="mt-7 border-t border-[#e5e7eb] pt-4 text-xs font-bold uppercase tracking-[.16em] text-[#737373]">{{ $review->product->name ?? '' }}</p>
                </article>
            @empty
                <div class="arcade-card col-span-full py-12 text-center text-sm text-[#737373]">Belum ada ulasan yang disetujui.</div>
            @endforelse
        </div>

        <div class="mt-10">{{ $reviews->links() }}</div>

        <div class="mt-14 border border-black bg-[#f3e5df] px-7 py-10 text-center sm:px-12"><p class="text-xs font-bold uppercase tracking-[.25em] text-[#faa21f]">Your turn</p><h2 class="mt-3 text-3xl font-bold uppercase text-black">Find a book worth repeating.</h2><a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-7 uppercase">Shop the collection <span aria-hidden="true">↗</span></a></div>
    </section>
@endsection
