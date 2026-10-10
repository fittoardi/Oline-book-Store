@extends('layouts.app')

@section('title', 'Shop by genre — '.config('storefront.brand.name'))
@section('meta_description', 'Explore Kembang Books by genre and subject.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Browse the signal</p>
            <h1 class="mt-4 text-5xl font-bold uppercase leading-[1.05] sm:text-6xl">Choose your genre.</h1>
            <p class="mt-6 max-w-xl text-sm leading-7 text-[#333333]">Dari cerita yang tenang hingga ide yang menantang, temukan bacaan yang ingin kamu bawa pulang.</p>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2">
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="arcade-card group relative min-h-72 overflow-hidden p-8 transition hover:border-black">
                    <div class="relative flex h-full flex-col justify-between">
                        <span class="text-xs uppercase tracking-[.25em] text-[#737373]">Collection 0{{ $loop->iteration }}</span>
                        <div>
                            <h2 class="text-3xl font-bold uppercase group-hover:underline">{{ $category->name }}</h2>
                            <p class="mt-3 max-w-xs text-sm leading-6 text-[#737373]">{{ $category->description }}</p>
                            <span class="mt-6 inline-flex arcade-tag">{{ $category->products_count }} titles</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="arcade-card col-span-full py-12 text-center text-sm text-[#737373]">Belum ada kategori aktif.</div>
            @endforelse
        </div>
    </section>
@endsection
