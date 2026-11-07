@extends('layouts.app')

@section('title', 'My Profile — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-black pb-6">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Your account</p>
            <h1 class="mt-3 text-4xl font-bold uppercase">My profile</h1>
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

        {{-- Account Metrics --}}
        <div class="mt-8 grid gap-5 sm:grid-cols-3">
            <a href="{{ route('orders.index') }}" class="arcade-card hover:border-black transition">
                <span class="text-xs text-[#737373] uppercase font-bold">Orders</span>
                <strong class="mt-3 block text-3xl font-bold">{{ $orders }}</strong>
                <span class="mt-2 block text-xs uppercase font-bold text-[#faa21f]">View order history ↗</span>
            </a>
            <a href="{{ route('wishlist.index') }}" class="arcade-card hover:border-black transition">
                <span class="text-xs text-[#737373] uppercase font-bold">Wishlist</span>
                <strong class="mt-3 block text-3xl font-bold">{{ $wishlistCount }}</strong>
                <span class="mt-2 block text-xs uppercase font-bold text-[#faa21f]">Saved books ↗</span>
            </a>
            <a href="{{ route('addresses.index') }}" class="arcade-card hover:border-black transition">
                <span class="text-xs text-[#737373] uppercase font-bold">Delivery</span>
                <strong class="mt-3 block text-3xl font-bold">+</strong>
                <span class="mt-2 block text-xs uppercase font-bold text-[#faa21f]">Manage addresses ↗</span>
            </a>
        </div>

        {{-- Details Form & Customer Care --}}
        <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
            <section class="arcade-card">
                <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Personal Details</h2>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase" for="name">Full Name</label>
                        <input id="name" name="name" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" value="{{ old('name', $user?->name) }}" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase" for="email">Email Address</label>
                        <input id="email" class="mt-2 w-full border border-[#e5e7eb] bg-[#f3e5df] px-3 py-3 text-sm text-[#737373]" type="email" value="{{ $user?->email }}" readonly title="Email cannot be changed">
                        <span class="mt-1 block text-[10px] text-[#737373]">Email account identifier cannot be edited.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase" for="phone">Phone Number</label>
                        <input id="phone" name="phone" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" value="{{ old('phone', $user?->phone) }}" required autocomplete="tel">
                    </div>

                    <div class="border-t border-[#e5e7eb] pt-4">
                        <p class="text-xs font-bold uppercase text-[#faa21f]">Change Password (Optional)</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[11px] font-bold uppercase" for="password">New Password</label>
                                <input id="password" name="password" type="password" class="mt-1 w-full border border-black bg-[#f3e5df] px-3 py-2 text-sm" placeholder="Min. 8 characters">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase" for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 w-full border border-black bg-[#f3e5df] px-3 py-2 text-sm" placeholder="Re-enter password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="arcade-button arcade-button-primary uppercase font-bold py-3 px-6 text-sm">
                        Save Changes ↗
                    </button>
                </form>
            </section>

            <section class="arcade-card h-fit space-y-4">
                <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Need Help?</h2>
                <p class="text-sm leading-6 text-[#737373]">
                    Tim layanan pelanggan Kembang Books siap membantu pertanyaan terkait kurasi buku, metode pembayaran, atau status pesanan Anda.
                </p>
                <div class="space-y-2 pt-2">
                    <a href="{{ route('chat.index') }}" class="arcade-button arcade-button-primary block text-center uppercase font-bold py-3 text-sm">
                        Open Customer Care ↗
                    </a>
                    <a href="{{ route('help.index') }}" class="arcade-button arcade-button-ghost block text-center uppercase text-sm py-2">
                        Help & FAQs
                    </a>
                </div>
            </section>
        </div>
    </section>
@endsection
