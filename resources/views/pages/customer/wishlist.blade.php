@extends('layouts.app')

@section('title', 'Wishlist — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-black pb-6"><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Saved for later</p><h1 class="mt-3 text-4xl font-bold uppercase">Wishlist</h1></div>
        @if(session('status'))<div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm">{{ session('status') }}</div>@endif
        @if($products->isEmpty())
            <div class="arcade-card mt-8 py-16 text-center"><p class="text-lg font-bold uppercase">Your wishlist is empty.</p><p class="mt-2 text-sm text-[#737373]">Save a book and come back when you are ready.</p><a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-6 inline-flex uppercase">Shop now</a></div>
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">@foreach($products as $product)<article class="arcade-card"><a href="{{ route('products.show', $product) }}">@if($product->primaryImage)<img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" class="aspect-[4/5] w-full object-cover">@endif<h2 class="mt-3 text-sm font-bold uppercase">{{ $product->name }}</h2><p class="mt-2 text-sm">Rp{{ number_format($product->price, 0, ',', '.') }}</p></a><form method="POST" action="{{ route('wishlist.toggle') }}" class="mt-4">@csrf<input type="hidden" name="slug" value="{{ $product->slug }}"><button class="arcade-button arcade-button-ghost w-full uppercase" type="submit">Remove</button></form></article>@endforeach</div>
        @endif
    </section>
@endsection
