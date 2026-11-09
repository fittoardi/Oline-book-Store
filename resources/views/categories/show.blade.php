@extends('layouts.app')

@section('title', $category->name.' books — Daily Books')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <a href="{{ route('categories.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">← All categories</a>

        <div class="mt-8 rounded-[6px] border border-[#6b2e12] bg-[#402011] p-8 sm:p-12">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#9f531b]">Daily Collection</p>
            <h1 class="font-display mt-3 text-6xl uppercase tracking-tight text-[#faae33] sm:text-7xl">{{ $category->name }}</h1>
            <p class="mt-4 max-w-xl text-sm leading-7 text-[#faae33] opacity-85">{{ $category->description }}</p>
        </div>

        <div class="mt-16 flex items-end justify-between gap-6 border-b border-[#6b2e12] pb-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9f531b]">{{ $products->total() }} titles</p>
                <h2 class="font-display mt-2 text-3xl uppercase tracking-tight text-[#faae33] sm:text-4xl">Made for your rhythm</h2>
            </div>
            <a href="{{ route('products.index') }}" class="hidden text-xs font-bold uppercase tracking-wider text-[#faae33] sm:block hover:underline">View all titles ↗</a>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product) }}" class="tiger-card group block">
                    <div class="aspect-[4/5] overflow-hidden rounded-[4px] border border-[#6b2e12] bg-[#281006]">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        @endif
                    </div>
                    <p class="mt-4 text-[10px] uppercase tracking-wider text-[#9f531b] font-bold">{{ $product->category?->name }} · {{ $product->author }}</p>
                    <h3 class="mt-1 text-base font-bold uppercase text-[#faae33] truncate">{{ $product->name }}</h3>
                    <p class="mt-2 text-sm font-bold text-[#faae33]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </a>
            @empty
                <div class="tiger-card col-span-full py-12 text-center text-xs text-[#faae33] opacity-75">Belum ada buku di kategori ini.</div>
            @endforelse
        </div>

        <div class="mt-10">{{ $products->links() }}</div>
    </section>
@endsection
