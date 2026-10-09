@extends('layouts.app')

@section('title', 'Addresses — Kembang Books')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-black pb-6"><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Delivery details</p><h1 class="mt-3 text-4xl font-bold uppercase">Saved addresses</h1></div>
        @if(session('status'))<div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm">{{ session('status') }}</div>@endif
        <div class="mt-8 grid gap-5 lg:grid-cols-[1fr_360px]">
            <div class="space-y-3">@forelse($addresses as $address)<article class="arcade-card"><div class="flex items-start justify-between gap-4"><div><span class="arcade-tag">{{ $address['label'] }}</span><h2 class="mt-4 text-sm font-bold uppercase">{{ $address['recipient_name'] }}</h2><p class="mt-2 text-sm leading-6 text-[#737373]">{{ $address['phone'] }}<br>{{ $address['address'] }}<br>{{ $address['city'] }}, {{ $address['postal_code'] }}</p></div><span class="text-xs text-[#737373]">Saved</span></div></article>@empty<div class="arcade-card py-12 text-center text-sm text-[#737373]">No saved addresses yet. Add one for faster checkout.</div>@endforelse</div>
            <form method="POST" action="{{ route('addresses.store') }}" class="arcade-card h-fit"><h2 class="text-lg font-bold uppercase">Add address</h2><div class="mt-5 space-y-4"><label class="block text-sm">Label<input name="label" value="{{ old('label', 'Rumah') }}" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3" placeholder="Rumah / Kantor"></label><label class="block text-sm">Recipient name<input name="recipient_name" value="{{ old('recipient_name') }}" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3"></label><label class="block text-sm">Phone<input name="phone" value="{{ old('phone') }}" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3" autocomplete="tel"></label><label class="block text-sm">Full address<textarea name="address" rows="3" required class="mt-2 w-full resize-none border border-black bg-[#f3e5df] px-3 py-3"></textarea></label><div class="grid grid-cols-2 gap-3"><label class="text-sm">City<input name="city" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3"></label><label class="text-sm">Postal code<input name="postal_code" required pattern="[0-9]{5}" maxlength="5" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3"></label></div>@csrf<button class="arcade-button arcade-button-primary w-full uppercase" type="submit">Save address</button></div></form>
        </div>
    </section>
@endsection
