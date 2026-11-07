@extends('layouts.app')

@section('title', 'Cart — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4 border-b border-black pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Shopping cart</p>
                <h1 class="mt-3 text-4xl font-bold uppercase">Your bag</h1>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm font-bold uppercase hover:underline">Continue shopping</a>
        </div>

        @if(session('status'))
            <div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm font-bold text-black">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 border border-black bg-red-100 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($items->isEmpty())
            <div class="arcade-card mt-8 py-16 text-center">
                <p class="text-lg font-bold uppercase">Your cart is empty.</p>
                <p class="mt-2 text-sm text-[#737373]">Start with a book that feels like you.</p>
                <a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-6 inline-flex uppercase">Shop now</a>
            </div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_320px]">
                <div class="space-y-4">
                    @foreach($items as $item)
                        @php($product = $item->productVariant->product)
                        @php($image = $product->primaryImage?->path)
                        <article class="arcade-card flex gap-4">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $product->name }}" width="120" height="150" class="h-28 w-24 object-cover border border-[#e5e7eb] sm:h-36 sm:w-28">
                            @endif
                            <div class="flex min-w-0 flex-1 flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <div>
                                    <p class="text-[10px] uppercase text-[#737373]">{{ $product->category?->name }} · {{ ucfirst(str_replace('_', ' ', $item->productVariant->format)) }}</p>
                                    <a href="{{ route('products.show', $product) }}" class="mt-1 block text-sm font-bold uppercase hover:underline">
                                        {{ $product->name }}
                                    </a>
                                    <p class="mt-1 text-xs text-[#737373]">{{ $product->author }}</p>
                                    <p class="mt-2 text-sm font-bold">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('cart.items.update', $product->slug) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="format" value="{{ $item->productVariant->format }}">
                                        <label class="sr-only" for="quantity-{{ $item->id }}">Quantity</label>
                                        <input id="quantity-{{ $item->id }}" name="quantity" type="number" min="1" max="99" value="{{ $item->quantity }}" class="w-16 border border-black bg-[#f3e5df] px-2 py-2 text-center text-sm font-bold">
                                        <button class="arcade-button arcade-button-ghost text-xs uppercase" type="submit">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.items.destroy', $product->slug) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="arcade-button arcade-button-ghost text-xs px-3" type="submit" aria-label="Remove {{ $product->name }}">✕</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="arcade-card h-fit lg:sticky lg:top-4">
                    <p class="text-xs font-bold uppercase text-[#737373]">Order summary</p>
                    <div class="mt-5 flex justify-between border-b border-[#e5e7eb] pb-4 text-sm">
                        <span>Items Total</span>
                        <span>{{ $items->sum('quantity') }}</span>
                    </div>
                    <div class="flex justify-between py-4 text-lg font-bold">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    @php($freeRemaining = max(0, config('storefront.shipping.free_threshold') - $subtotal))
                    @if($freeRemaining === 0)
                        <div class="border-y border-black py-3 text-xs font-bold text-[#128e44] text-center">
                            ✓ Anda mendapatkan GRATIS ONGKIR!
                        </div>
                    @else
                        <div class="border-y border-[#e5e7eb] py-3 text-xs text-[#737373]">
                            Tambah <strong class="text-black">Rp{{ number_format($freeRemaining, 0, ',', '.') }}</strong> lagi untuk mendapatkan Bebas Ongkir.
                        </div>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="arcade-button arcade-button-primary mt-5 flex justify-center uppercase font-bold py-3 text-center">
                        Proceed to checkout ↗
                    </a>
                </aside>
            </div>
        @endif
    </section>
@endsection
