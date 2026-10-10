@extends('layouts.app')

@section('title', $title.' — Kembang Books')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8"><div class="border border-black bg-[#f3e5df] p-5 sm:p-8"><div class="arcade-checkerboard border border-black p-5 sm:p-8"><div class="border border-black bg-[#f3e5df] p-6 sm:p-10"><p class="text-xs font-bold uppercase text-[#faa21f]">{{ config('storefront.brand.name') }}</p><h1 class="mt-3 text-4xl font-bold uppercase">{{ $title }}</h1><p class="mt-4 max-w-xl text-sm leading-7 text-[#737373]">{{ $description }}</p><div class="mt-8 grid gap-3 sm:grid-cols-3">@foreach($cards as $card)<div class="border border-black bg-[#f3e5df] p-5"><span class="text-2xl font-bold">{{ $card[0] }}</span><h2 class="mt-5 text-sm font-bold uppercase">{{ $card[1] }}</h2><p class="mt-2 text-sm leading-6 text-[#737373]">{{ $card[2] }}</p></div>@endforeach</div><a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-8 inline-flex uppercase">Browse the shelf ↗</a></div></div></div></section>
@endsection
