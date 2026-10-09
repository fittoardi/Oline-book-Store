@extends('layouts.app')

@section('title', 'Shop by genre — Kembang Books')
@section('meta_description', 'Explore Kembang Books by genre and subject.')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-24 pt-16 lg:px-8 lg:pt-24">
        <div class="max-w-2xl kh-fade-up">
            <p class="text-xs font-semibold uppercase tracking-[.3em] text-[#b99a62]">Find your everyday</p>
            <h1 class="mt-4 text-5xl font-semibold leading-[1.05] tracking-tight text-[#493b36] sm:text-6xl">Choose your texture.</h1>
            <p class="mt-6 max-w-xl text-base leading-8 text-[#6f5146]/70">Dari material ringan untuk hari yang panjang hingga silk untuk momen spesial, temukan koleksi yang paling cocok dengan caramu bergerak.</p>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category['slug']) }}" class="group relative min-h-72 overflow-hidden rounded-[2rem] bg-gradient-to-br {{ $category['tone'] }} p-8 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#6f5146]/10">
                    <div class="absolute -bottom-16 -right-10 h-48 w-48 rounded-full border border-white/35 bg-white/20 transition duration-500 group-hover:scale-110"></div>
                    <div class="relative flex h-full flex-col justify-between">
                        <span class="text-xs font-semibold uppercase tracking-[.25em] text-[#493b36]/65">Collection 0{{ $loop->iteration }}</span>
                        <div>
                            <h2 class="text-3xl font-semibold text-[#493b36]">{{ $category['name'] }}</h2>
                            <p class="mt-3 max-w-xs text-sm leading-6 text-[#493b36]/70">{{ $category['description'] }}</p>
                            <span class="mt-6 inline-flex text-sm font-semibold text-[#493b36]">Explore {{ $category['name'] }} <span class="ml-2">↗</span></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
