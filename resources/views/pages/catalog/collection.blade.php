@extends('layouts.app')

@section('title', 'Collections — '.config('storefront.brand.name'))
@section('meta_description', 'Explore curated Daily Books collections for every kind of reader.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">The Daily Edit</p>
                <h1 class="font-display mt-4 max-w-3xl text-5xl uppercase leading-[0.88] tracking-tight text-[#faae33] sm:text-7xl">
                    A Shelf For Every State Of Mind.
                </h1>
            </div>
            <p class="max-w-sm text-sm leading-7 text-[#faae33] opacity-85 lg:self-end">
                Dari cerita yang tenang hingga ide yang menantang, temukan bacaan yang ingin kamu bawa pulang.
            </p>
        </div>

        <div class="mt-12 grid gap-5 sm:grid-cols-3">
            @foreach($categories as $collection)
                <a href="{{ route('categories.show', $collection) }}" class="tiger-card group flex min-h-64 flex-col justify-between p-6">
                    <p class="text-xs uppercase tracking-wider text-[#9f531b] font-bold">{{ $collection->products_count }} titles</p>
                    <div>
                        <h2 class="text-3xl font-bold uppercase text-[#faae33] group-hover:underline">{{ $collection->name }}</h2>
                        <p class="mt-3 text-xs leading-5 text-[#faae33] opacity-80">{{ $collection->description }}</p>
                    </div>
                    <span class="mt-6 inline-flex tiger-button tiger-button-ghost !px-4 !py-1 text-xs self-start">Explore ↗</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="border-t border-dotted border-[#faae33]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between border-b border-[#6b2e12] pb-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#faae33]">Shop the edit</p>
                    <h2 class="mt-2 text-3xl font-bold uppercase tracking-tight text-[#faae33] sm:text-4xl">Made to be revisited</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">View all ↗</a>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="tiger-card group block">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" loading="lazy" class="aspect-[4/5] w-full rounded-[4px] border border-[#6b2e12] object-cover transition group-hover:scale-[1.02]">
                        @endif
                        <p class="mt-3 text-[10px] uppercase tracking-wider text-[#9f531b] font-bold">{{ $product->category?->name }}</p>
                        <h3 class="mt-1 text-sm font-bold uppercase text-[#faae33] truncate">{{ $product->name }}</h3>
                        <p class="mt-2 text-sm font-bold text-[#faae33]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
