@extends('layouts.app')

@section('title', 'Reviews — '.config('storefront.brand.name'))
@section('meta_description', 'Read what the Daily Books community says about their latest reads.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end border-b border-dotted border-[#faae33] pb-10">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">From The Community</p>
                <h1 class="font-display mt-4 text-6xl uppercase leading-[0.88] tracking-tight text-[#faae33] sm:text-7xl">Read And Loved.</h1>
                <p class="mt-4 max-w-xl text-sm leading-7 text-[#faae33] opacity-85">Cerita kecil dari orang-orang yang menjadikan Daily Books bagian dari rutinitas mereka.</p>
            </div>
            <div class="tiger-card-deep px-6 py-5 text-right border border-[#6b2e12]">
                <div class="font-display text-4xl text-[#faae33]">4.8 ★</div>
                <p class="mt-1 text-xs uppercase tracking-wider text-[#9f531b] font-bold">Loved by readers</p>
            </div>
        </div>

        <div class="mt-14 grid gap-6 lg:grid-cols-3">
            @forelse($reviews as $review)
                <article class="tiger-card flex flex-col justify-between p-7">
                    <div>
                        <div class="flex items-center justify-between border-b border-[#6b2e12] pb-4">
                            <div>
                                <h2 class="text-sm font-bold uppercase tracking-wider text-[#faae33]">{{ $review->user->name ?? 'Reader' }}</h2>
                                <p class="text-[11px] text-[#9f531b] font-semibold">Verified customer</p>
                            </div>
                            <span class="text-sm text-[#faae33]">{{ str_repeat('★', $review->rating) }}</span>
                        </div>
                        <p class="mt-6 text-sm leading-7 text-[#faae33] opacity-90">“{{ $review->comment }}”</p>
                    </div>
                    <p class="mt-8 border-t border-[#6b2e12] pt-4 text-xs font-bold uppercase tracking-wider text-[#9f531b]">{{ $review->product->name ?? '' }}</p>
                </article>
            @empty
                <div class="tiger-card col-span-full py-16 text-center text-xs text-[#faae33] opacity-75">Belum ada ulasan yang disetujui.</div>
            @endforelse
        </div>

        <div class="mt-10">{{ $reviews->links() }}</div>

        <div class="mt-20 rounded-[6px] border border-[#6b2e12] bg-[#402011] px-7 py-16 text-center sm:px-12">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Your Turn</p>
            <h2 class="font-display mt-3 text-4xl uppercase leading-tight text-[#faae33] sm:text-5xl">Find A Book Worth Repeating.</h2>
            <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary mt-8">Shop the collection ↗</a>
        </div>
    </section>
@endsection
