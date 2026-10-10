@extends('layouts.app')

@section('title', 'Cart — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4 border-b border-black pb-6">
            <div><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Shopping cart</p><h1 class="mt-3 text-4xl font-bold uppercase">Your bag</h1></div>
            <a href="{{ route('products.index') }}" class="text-sm font-bold uppercase hover:underline">Continue shopping</a>
        </div>

        @if(session('status'))<div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm">{{ session('status') }}</div>@endif

        @if($items->isEmpty())
            <div class="arcade-card mt-8 py-16 text-center"><p class="text-lg font-bold uppercase">Your cart is empty.</p><p class="mt-2 text-sm text-[#737373]">Start with a book that feels like you.</p><a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-6 inline-flex uppercase">Shop now</a></div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_320px]">
                <div class="space-y-3">
                    @foreach($items as $item)
                        @php($product = $item->productVariant->product)
                        @php($image = $product->primaryImage?->path)
                        <article class="arcade-card flex gap-4">
                            @if($image)<img src="{{ $image }}" alt="{{ $product->name }}" width="120" height="150" class="h-28 w-24 object-cover sm:h-36 sm:w-28">@endif
                            <div class="flex min-w-0 flex-1 flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <div><p class="text-[10px] uppercase text-[#737373]">{{ $product->category?->name }} · {{ ucfirst(str_replace('_', ' ', $item->productVariant->format)) }}</p><h2 class="mt-1 text-sm font-bold uppercase">{{ $product->name }}</h2><p class="mt-2 text-sm">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p></div>
                                <div class="flex items-center gap-3"><form method="POST" action="{{ route('cart.items.update', $product->slug) }}" class="flex items-center gap-2">@csrf @method('PATCH')<input type="hidden" name="format" value="{{ $item->productVariant->format }}"><label class="sr-only" for="quantity-{{ $item->id }}">Quantity</label><input id="quantity-{{ $item->id }}" name="quantity" type="number" min="1" max="99" value="{{ $item->quantity }}" class="w-16 border border-black bg-[#f3e5df] px-2 py-2 text-center"><button class="arcade-button arcade-button-ghost px-2" type="submit">Update</button></form><form method="POST" action="{{ route('cart.items.destroy', $product->slug) }}">@csrf @method('DELETE')<button class="arcade-button arcade-button-ghost px-2" type="submit" aria-label="Remove {{ $product->name }}">X</button></form></div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <aside class="arcade-card h-fit lg:sticky lg:top-4"><p class="text-xs font-bold uppercase text-[#737373]">Order summary</p><div class="mt-5 flex justify-between border-b border-[#e5e7eb] pb-4 text-sm"><span>Items</span><span>{{ $items->sum('quantity') }}</span></div><div class="flex justify-between py-4 text-lg font-bold"><span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div><p class="border-y border-[#e5e7eb] py-3 text-xs text-[#737373]">Add Rp{{ number_format(max(0, config('storefront.shipping.free_threshold') - $subtotal), 0, ',', '.') }} more for free shipping.</p><a href="{{ route('checkout.index') }}" class="arcade-button arcade-button-primary mt-5 flex justify-center uppercase">Proceed to checkout</a></aside>
            </div>
        @endif
    </section>
@endsection
