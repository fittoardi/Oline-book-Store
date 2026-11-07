@extends('layouts.app')

@section('title', 'Addresses — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-black pb-6">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Delivery details</p>
            <h1 class="mt-3 text-4xl font-bold uppercase">Saved addresses</h1>
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

        <div class="mt-8 grid gap-5 lg:grid-cols-[1fr_360px]">
            <div class="space-y-4">
                @forelse($addresses as $address)
                    <article class="arcade-card flex flex-col justify-between sm:flex-row sm:items-start gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="arcade-tag">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="arcade-tag bg-[#128e44] text-white">Default</span>
                                @endif
                            </div>
                            <h2 class="mt-3 text-sm font-bold uppercase">{{ $address->recipient_name }}</h2>
                            <p class="mt-1 text-xs text-[#737373]">{{ $address->phone }}</p>
                            <p class="mt-2 text-xs leading-5 text-[#333333]">
                                {{ $address->full_address }}<br>
                                {{ $address->city }}, {{ $address->postal_code }}
                            </p>
                        </div>
                        @if(! $address->is_default)
                            <form method="POST" action="{{ route('addresses.default', $address->id) }}">
                                @csrf
                                <button type="submit" class="arcade-button arcade-button-ghost text-xs uppercase">
                                    Set Default
                                </button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="arcade-card py-12 text-center text-sm text-[#737373]">
                        Belum ada alamat tersimpan. Tambahkan alamat untuk mempercepat proses checkout.
                    </div>
                @endforelse
            </div>

            <form method="POST" action="{{ route('addresses.store') }}" class="arcade-card h-fit">
                @csrf
                <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Add New Address</h2>
                <div class="mt-5 space-y-4">
                    <label class="block text-xs font-bold uppercase">
                        Label
                        <input name="label" value="{{ old('label', 'Rumah') }}" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" placeholder="Rumah / Kantor / Kos">
                    </label>
                    <label class="block text-xs font-bold uppercase">
                        Recipient name
                        <input name="recipient_name" value="{{ old('recipient_name') }}" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm">
                    </label>
                    <label class="block text-xs font-bold uppercase">
                        Phone
                        <input name="phone" value="{{ old('phone') }}" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" autocomplete="tel">
                    </label>
                    <label class="block text-xs font-bold uppercase">
                        Full address
                        <textarea name="address" rows="3" required class="mt-2 w-full resize-none border border-black bg-[#f3e5df] px-3 py-3 text-sm" placeholder="Nama jalan, gedung, nomor rumah, RT/RW, kecamatan">{{ old('address') }}</textarea>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="text-xs font-bold uppercase">
                            City
                            <input name="city" required class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" value="{{ old('city', 'Jakarta') }}">
                        </label>
                        <label class="text-xs font-bold uppercase">
                            Postal code
                            <input name="postal_code" required pattern="[0-9]{5}" maxlength="5" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" value="{{ old('postal_code') }}">
                        </label>
                    </div>
                    <button class="arcade-button arcade-button-primary w-full uppercase font-bold py-3 text-sm" type="submit">
                        Save address ↗
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
