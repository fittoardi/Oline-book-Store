@extends('layouts.app')

@section('title', $title.' — Daily Books')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="tiger-card-deep p-6 sm:p-10 border border-[#6b2e12]">
            <div class="rounded-[6px] border border-[#6b2e12] bg-[#402011] p-6 sm:p-10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#9f531b]">{{ config('storefront.brand.name') }}</p>
                <h1 class="font-display mt-3 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">{{ $title }}</h1>
                <p class="mt-4 max-w-xl text-sm leading-7 text-[#faae33] opacity-85">{{ $description }}</p>
                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    @foreach($cards as $card)
                        <div class="rounded-[6px] border border-[#6b2e12] bg-[#281006] p-5">
                            <span class="font-display text-3xl text-[#faae33]">{{ $card[0] }}</span>
                            <h2 class="mt-4 text-xs font-bold uppercase tracking-wider text-[#faae33]">{{ $card[1] }}</h2>
                            <p class="mt-2 text-xs leading-5 text-[#faae33] opacity-75">{{ $card[2] }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary mt-10">Browse the shelf ↗</a>
            </div>
        </div>
    </section>
@endsection
