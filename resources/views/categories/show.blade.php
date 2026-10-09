@extends('layouts.app')

@section('title', $category['name'].' books — Kembang Books')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-24 pt-16 lg:px-8 lg:pt-24">
        <a href="{{ route('categories.index') }}" class="text-sm text-[#6f5146]/60 transition hover:text-[#6f5146]">← All categories</a>
        <div class="mt-8 rounded-[2rem] bg-gradient-to-br {{ $category['tone'] }} px-7 py-12 sm:px-12">
            <p class="text-xs font-semibold uppercase tracking-[.3em] text-[#493b36]/65">Kembang collection</p>
            <h1 class="mt-3 text-5xl font-semibold tracking-tight text-[#493b36]">{{ $category['name'] }}</h1>
            <p class="mt-4 max-w-xl leading-7 text-[#493b36]/70">{{ $category['description'] }}</p>
        </div>

        <div class="mt-14 flex items-end justify-between gap-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[.25em] text-[#b99a62]">{{ $products->count() }} pieces</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-[#493b36]">Made for your rhythm</h2>
            </div>
            <a href="{{ route('products.index') }}" class="hidden text-sm font-semibold text-[#6f5146] sm:block">View all pieces ↗</a>
        </div>

        <div class="mt-8 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product['slug']) }}" class="group">
                    <div class="aspect-[4/5] overflow-hidden rounded-[2rem] bg-gradient-to-br {{ $product['color'] }} p-6">
                        <div class="flex h-full items-end rounded-[1.5rem] border border-white/35 bg-white/10 p-5 transition duration-500 group-hover:scale-[1.02]"><span class="text-xs font-semibold uppercase tracking-[.2em] text-white/80">{{ $product['tag'] ?? 'Everyday essential' }}</span></div>
                    </div>
                    <p class="mt-4 text-[10px] font-semibold uppercase tracking-[.2em] text-[#6f5146]/50">{{ $product['category'] }}</p>
                    <h3 class="mt-1 font-semibold text-[#2e2927]">{{ $product['name'] }}</h3>
                    <p class="mt-2 text-sm font-semibold text-[#6f5146]">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endsection
