@extends('layouts.app')

@section('title', 'Cart — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4 border-b border-dotted border-[#faae33] pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Shopping Cart</p>
                <h1 class="font-display mt-2 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">Your Bag</h1>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">Continue shopping</a>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-[6px] border border-[#faae33] bg-[#faae33] px-5 py-3 text-xs font-bold uppercase text-[#281006]">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-[6px] border border-[#d1255c] bg-[#281006] p-4 text-xs text-[#faae33]">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($items->isEmpty())
            <div class="tiger-card mt-8 py-20 text-center">
                <p class="font-display text-4xl uppercase text-[#faae33]">Your cart is empty.</p>
                <p class="mt-2 text-xs text-[#faae33] opacity-75">Start with a book that feels like you.</p>
                <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary mt-6">Shop now</a>
            </div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]">
                <div class="space-y-4">
                    @foreach($items as $item)
                        @php($product = $item->productVariant->product)
                        @php($image = $product->primaryImage?->path)
                        <article class="tiger-card flex gap-4 p-5">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $product->name }}" width="120" height="150" class="h-28 w-24 rounded-[4px] border border-[#6b2e12] object-cover sm:h-36 sm:w-28">
                            @endif
                            <div class="flex min-w-0 flex-1 flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-[#9f531b] font-bold">{{ $product->category?->name }} · {{ ucfirst(str_replace('_', ' ', $item->productVariant->format)) }}</p>
                                    <a href="{{ route('products.show', $product) }}" class="mt-1 block text-base font-bold uppercase text-[#faae33] hover:underline">
                                        {{ $product->name }}
                                    </a>
                                    <p class="mt-1 text-xs text-[#faae33] opacity-75">{{ $product->author }}</p>
                                    <p class="mt-2 text-sm font-bold text-[#faae33]">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('cart.items.update', $product->slug) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="format" value="{{ $item->productVariant->format }}">
                                        <label class="sr-only" for="quantity-{{ $item->id }}">Quantity</label>
                                        <input id="quantity-{{ $item->id }}" name="quantity" type="number" min="1" max="99" value="{{ $item->quantity }}" class="w-16 rounded-full border border-[#6b2e12] bg-[#281006] px-2 py-2 text-center text-xs font-bold text-[#faae33]">
                                        <button class="tiger-button tiger-button-ghost !px-3 !py-1 text-xs" type="submit">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.items.destroy', $product->slug) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="tiger-button tiger-button-ghost !px-3 !py-1 text-xs" type="submit" aria-label="Remove {{ $product->name }}">✕</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="tiger-card h-fit lg:sticky lg:top-4 p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#9f531b]">Order Summary</p>
                    <div class="mt-5 flex justify-between border-b border-[#6b2e12] pb-4 text-xs font-semibold text-[#faae33]">
                        <span>Items Total</span>
                        <span>{{ $items->sum('quantity') }}</span>
                    </div>
                    <div class="flex justify-between py-4 text-base font-bold text-[#faae33]">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    @php($freeRemaining = max(0, config('storefront.shipping.free_threshold') - $subtotal))
                    @if($freeRemaining === 0)
                        <div class="border-y border-dotted border-[#faae33] py-3 text-xs font-bold text-[#faae33] text-center">
                            ✓ Anda mendapatkan GRATIS ONGKIR!
                        </div>
                    @else
                        <div class="border-y border-[#6b2e12] py-3 text-xs text-[#faae33] opacity-80">
                            Tambah <strong class="text-[#faae33]">Rp{{ number_format($freeRemaining, 0, ',', '.') }}</strong> lagi untuk mendapatkan Bebas Ongkir.
                        </div>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="tiger-button tiger-button-primary mt-6 w-full text-center py-3">
                        Proceed to checkout ↗
                    </a>
                </aside>
            </div>
        @endif
    </section>
@endsection
