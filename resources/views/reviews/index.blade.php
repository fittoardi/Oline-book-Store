@extends('layouts.app')

@section('title', 'Reviews — Kembang Books')
@section('meta_description', 'Read what the Kembang Books community says about their latest reads.')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-24 pt-16 lg:px-8 lg:pt-24">
        <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[.3em] text-[#b99a62]">From the community</p>
                <h1 class="mt-4 text-5xl font-semibold leading-[1.05] tracking-tight text-[#493b36] sm:text-6xl">Worn, loved, repeated.</h1>
                <p class="mt-6 max-w-xl leading-8 text-[#6f5146]/70">Cerita kecil dari orang-orang yang menjadikan Kembang bagian dari rutinitas mereka.</p>
            </div>
            <div class="kh-glass rounded-2xl px-5 py-4 text-right"><div class="text-2xl font-semibold text-[#493b36]">4.8 <span class="text-[#b99a62]">★</span></div><p class="mt-1 text-xs text-[#6f5146]/60">Loved by our community</p></div>
        </div>

        <div class="mt-14 grid gap-5 lg:grid-cols-3">
            @foreach($reviews as $review)
                <article class="kh-card rounded-[2rem] p-7 transition hover:-translate-y-1">
                    <div class="flex items-center justify-between"><div class="flex items-center gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#e8cfcf] text-sm font-semibold text-[#6f5146]">{{ $review['initials'] }}</span><div><h2 class="text-sm font-semibold text-[#493b36]">{{ $review['name'] }}</h2><p class="text-xs text-[#6f5146]/50">Verified customer</p></div></div><span class="text-sm tracking-[.15em] text-[#b99a62]">{{ str_repeat('★', $review['rating']) }}</span></div>
                    <p class="mt-8 text-lg leading-8 text-[#493b36]">“{{ $review['quote'] }}”</p>
                    <p class="mt-7 border-t border-[#6f5146]/10 pt-4 text-xs font-semibold uppercase tracking-[.16em] text-[#6f5146]/50">{{ $review['product'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="mt-14 rounded-[2rem] bg-[#e9ded2]/65 px-7 py-10 text-center sm:px-12"><p class="text-xs font-semibold uppercase tracking-[.25em] text-[#b99a62]">Your turn</p><h2 class="mt-3 text-3xl font-semibold text-[#493b36]">Find a piece worth repeating.</h2><a href="{{ route('products.index') }}" class="kh-btn kh-btn-primary mt-7">Shop the collection <span aria-hidden="true">↗</span></a></div>
    </section>
@endsection
