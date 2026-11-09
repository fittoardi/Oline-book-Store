@extends('layouts.app')

@section('title', 'Wishlist — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-dotted border-[#faae33]/40 pb-6">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faae33]">Saved For Later</p>
            <h1 class="mt-2 text-4xl sm:text-5xl font-bold uppercase tracking-tight text-[#faae33] font-['Bebas_Neue',sans-serif]">Wishlist</h1>
            <p class="mt-2 text-sm text-[#faae33]/80">Your curated queue of coveted titles, ready to transfer to your cart.</p>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-[6px] border border-[#faae33] bg-[#281006] px-5 py-4 text-sm font-bold text-[#faae33]">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-[6px] border border-[#d1255c] bg-[#402011] p-4 text-sm text-[#faae33]">
                <ul class="list-disc pl-5 space-y-1 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($products->isEmpty())
            <div class="tiger-card mt-8 py-16 text-center">
                <p class="text-2xl font-bold uppercase text-[#faae33] font-['Bebas_Neue',sans-serif]">Your wishlist is currently empty.</p>
                <p class="mt-2 text-sm text-[#faae33]/70">Explore the Daily Books catalog and bookmark volumes for your personal library.</p>
                <a href="{{ route('products.index') }}" class="tiger-button-primary mt-6 inline-flex uppercase text-xs tracking-wider">Browse Books ↗</a>
            </div>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($products as $product)
                    <article class="tiger-card flex flex-col justify-between group">
                        <a href="{{ route('products.show', $product) }}" class="block">
                            <div class="aspect-[4/5] overflow-hidden rounded-[4px] bg-[#281006] border border-[#6b2e12]">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="900" height="1125" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs uppercase tracking-widest text-[#faae33]/40">
                                        No Cover
                                    </div>
                                @endif
                            </div>
                            <p class="mt-3 text-[10px] uppercase font-bold tracking-wider text-[#faae33]/70">{{ $product->category?->name }}</p>
                            <h2 class="mt-1 text-sm font-bold uppercase tracking-tight text-[#faae33] line-clamp-1">{{ $product->name }}</h2>
                            <p class="mt-1 text-xs text-[#faae33]/60 italic">{{ $product->author }}</p>
                            <p class="mt-2 text-sm font-bold text-[#faae33]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        </a>

                        <div class="mt-4 space-y-2 border-t border-dotted border-[#faae33]/25 pt-3">
                            <form method="POST" action="{{ route('wishlist.move-to-cart', $product->slug) }}">
                                @csrf
                                <button class="tiger-button-primary w-full uppercase text-xs font-bold py-2 tracking-wider" type="submit">
                                    Move to cart ↗
                                </button>
                            </form>
                            <form method="POST" action="{{ route('wishlist.toggle') }}">
                                @csrf
                                <input type="hidden" name="slug" value="{{ $product->slug }}">
                                <button class="tiger-button-ghost w-full uppercase text-xs py-2 tracking-wider" type="submit">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
