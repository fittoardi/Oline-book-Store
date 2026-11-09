@extends('layouts.app')

@section('title', 'Shop by genre — '.config('storefront.brand.name'))
@section('meta_description', 'Explore Daily Books by genre and subject.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Browse The Shelves</p>
            <h1 class="font-display mt-4 text-6xl uppercase leading-[0.88] tracking-tight text-[#faae33] sm:text-7xl">Choose Your Genre.</h1>
            <p class="mt-6 max-w-xl text-sm leading-7 text-[#faae33] opacity-85">Dari cerita yang tenang hingga ide yang menantang, temukan bacaan yang ingin kamu bawa pulang.</p>
        </div>

        <div class="mt-14 grid gap-6 sm:grid-cols-2">
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="tiger-card group relative min-h-72 overflow-hidden p-8">
                    <div class="relative flex h-full flex-col justify-between">
                        <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#9f531b]">Collection 0{{ $loop->iteration }}</span>
                        <div>
                            <h2 class="font-display text-4xl uppercase text-[#faae33] group-hover:underline">{{ $category->name }}</h2>
                            <p class="mt-3 max-w-sm text-xs leading-6 text-[#faae33] opacity-80">{{ $category->description }}</p>
                            <span class="mt-6 inline-flex tiger-tag tiger-tag-gold">{{ $category->products_count }} titles</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="tiger-card col-span-full py-12 text-center text-xs text-[#faae33] opacity-75">Belum ada kategori aktif.</div>
            @endforelse
        </div>
    </section>
@endsection
