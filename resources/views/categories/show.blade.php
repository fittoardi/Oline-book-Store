@extends('layouts.app')

@section('title', $category->name.' books — Kembang Books')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('categories.index') }}" class="text-sm font-bold uppercase hover:underline">← All categories</a>
        <div class="mt-8 border border-black bg-[#f3e5df] p-6 sm:p-10">
            <p class="text-xs font-bold uppercase tracking-[.3em] text-[#faa21f]">Kembang collection</p>
            <h1 class="mt-3 text-5xl font-bold uppercase tracking-tight text-black">{{ $category->name }}</h1>
            <p class="mt-4 max-w-xl leading-7 text-[#333333]">{{ $category->description }}</p>
        </div>

        <div class="mt-14 flex items-end justify-between gap-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.25em] text-[#737373]">{{ $products->total() }} pieces</p>
                <h2 class="mt-2 text-3xl font-bold uppercase tracking-tight text-black">Made for your rhythm</h2>
            </div>
            <a href="{{ route('products.index') }}" class="hidden text-sm font-bold uppercase sm:block hover:underline">View all pieces ↗</a>
        </div>

        <div class="mt-8 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product) }}" class="arcade-card group block">
                    <div class="aspect-[4/5] overflow-hidden bg-[#e5e7eb]">@if($product->primaryImage)<img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">@endif</div>
                    <p class="mt-3 text-[10px] uppercase text-[#737373]">{{ $product->category?->name }} · {{ $product->author }}</p>
                    <h3 class="mt-1 text-sm font-bold uppercase">{{ $product->name }}</h3>
                    <p class="mt-2 text-sm">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </a>
            @empty
                <div class="arcade-card col-span-full py-12 text-center text-sm text-[#737373]">Belum ada buku di kategori ini.</div>
            @endforelse
        </div>

        <div class="mt-10">{{ $products->links() }}</div>
    </section>
@endsection
