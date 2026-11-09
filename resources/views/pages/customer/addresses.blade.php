@extends('layouts.app')

@section('title', 'Addresses — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-dotted border-[#faae33]/40 pb-6">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faae33]">Delivery Details</p>
            <h1 class="mt-2 text-4xl sm:text-5xl font-bold uppercase tracking-tight text-[#faae33] font-['Bebas_Neue',sans-serif]">Saved Addresses</h1>
            <p class="mt-2 text-sm text-[#faae33]/80">Manage delivery locations and destination presets for quick single-tap checkout.</p>
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

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_380px]">
            <div class="space-y-4">
                @forelse($addresses as $address)
                    <article class="tiger-card flex flex-col justify-between sm:flex-row sm:items-start gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="tiger-tag">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="tiger-tag-gold">Default Address</span>
                                @endif
                            </div>
                            <h2 class="mt-3 text-base font-bold uppercase text-[#faae33]">{{ $address->recipient_name }}</h2>
                            <p class="mt-1 text-xs text-[#faae33]/70 font-mono">{{ $address->phone }}</p>
                            <p class="mt-2 text-xs leading-5 text-[#faae33]/85">
                                {{ $address->full_address }}<br>
                                {{ $address->city }}, {{ $address->postal_code }}
                            </p>
                        </div>
                        @if(! $address->is_default)
                            <form method="POST" action="{{ route('addresses.default', $address->id) }}">
                                @csrf
                                <button type="submit" class="tiger-button-ghost text-xs uppercase">
                                    Set Default
                                </button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="tiger-card py-12 text-center text-sm text-[#faae33]/70">
                        Belum ada alamat tersimpan. Tambahkan alamat untuk mempercepat proses checkout.
                    </div>
                @endforelse
            </div>

            <form method="POST" action="{{ route('addresses.store') }}" class="tiger-card h-fit">
                @csrf
                <h2 class="text-xl font-bold uppercase tracking-tight text-[#faae33] font-['Bebas_Neue',sans-serif] border-b border-dotted border-[#faae33]/30 pb-3">
                    Add New Address
                </h2>
                <div class="mt-5 space-y-4 text-[#faae33]">
                    <label class="block text-xs font-bold uppercase tracking-wider">
                        Label
                        <input name="label" value="{{ old('label', 'Rumah') }}" required class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-2.5 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="Rumah / Kantor / Kos">
                    </label>
                    <label class="block text-xs font-bold uppercase tracking-wider">
                        Recipient Name
                        <input name="recipient_name" value="{{ old('recipient_name') }}" required class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-2.5 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none">
                    </label>
                    <label class="block text-xs font-bold uppercase tracking-wider">
                        Phone
                        <input name="phone" value="{{ old('phone') }}" required class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-2.5 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" autocomplete="tel">
                    </label>
                    <label class="block text-xs font-bold uppercase tracking-wider">
                        Full Address
                        <textarea name="address" rows="3" required class="mt-1.5 w-full rounded-[14px] border border-[#6b2e12] bg-[#281006] px-4 py-2.5 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none resize-none" placeholder="Nama jalan, gedung, nomor rumah, RT/RW, kecamatan">{{ old('address') }}</textarea>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="text-xs font-bold uppercase tracking-wider">
                            City
                            <input name="city" required class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-2.5 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" value="{{ old('city', 'Jakarta') }}">
                        </label>
                        <label class="text-xs font-bold uppercase tracking-wider">
                            Postal Code
                            <input name="postal_code" required pattern="[0-9]{5}" maxlength="5" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-2.5 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" value="{{ old('postal_code') }}">
                        </label>
                    </div>
                    <button class="tiger-button-primary w-full uppercase font-bold py-3 text-xs tracking-wider mt-2" type="submit">
                        Save Address ↗
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
