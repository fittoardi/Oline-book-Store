@extends('layouts.app')

@section('title', 'My Profile — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="border-b border-dotted border-[#faae33] pb-6">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Your Account</p>
            <h1 class="font-display mt-2 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">My Profile</h1>
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

        {{-- Account Metrics --}}
        <div class="mt-8 grid gap-5 sm:grid-cols-3">
            <a href="{{ route('orders.index') }}" class="tiger-card block p-6">
                <span class="text-[10px] text-[#9f531b] uppercase font-bold tracking-wider">Orders</span>
                <strong class="font-display mt-3 block text-4xl text-[#faae33]">{{ $orders }}</strong>
                <span class="mt-2 block text-xs font-bold uppercase tracking-wider text-[#faae33]">View order history ↗</span>
            </a>
            <a href="{{ route('wishlist.index') }}" class="tiger-card block p-6">
                <span class="text-[10px] text-[#9f531b] uppercase font-bold tracking-wider">Wishlist</span>
                <strong class="font-display mt-3 block text-4xl text-[#faae33]">{{ $wishlistCount }}</strong>
                <span class="mt-2 block text-xs font-bold uppercase tracking-wider text-[#faae33]">Saved books ↗</span>
            </a>
            <a href="{{ route('addresses.index') }}" class="tiger-card block p-6">
                <span class="text-[10px] text-[#9f531b] uppercase font-bold tracking-wider">Delivery</span>
                <strong class="font-display mt-3 block text-4xl text-[#faae33]">•</strong>
                <span class="mt-2 block text-xs font-bold uppercase tracking-wider text-[#faae33]">Manage addresses ↗</span>
            </a>
        </div>

        {{-- Details Form & Customer Care --}}
        <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
            <section class="tiger-card p-6 sm:p-8">
                <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-3">Personal Details</h2>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]" for="name">Full Name</label>
                        <input id="name" name="name" class="mt-2 w-full" value="{{ old('name', $user?->name) }}" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]" for="email">Email Address</label>
                        <input id="email" class="mt-2 w-full opacity-60 cursor-not-allowed" type="email" value="{{ $user?->email }}" readonly title="Email cannot be changed">
                        <span class="mt-1 block text-[10px] text-[#9f531b]">Email account identifier cannot be edited.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]" for="phone">Phone Number</label>
                        <input id="phone" name="phone" class="mt-2 w-full" value="{{ old('phone', $user?->phone) }}" required autocomplete="tel">
                    </div>

                    <div class="border-t border-[#6b2e12] pt-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-[#9f531b]">Change Password (Optional)</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#faae33]" for="password">New Password</label>
                                <input id="password" name="password" type="password" class="mt-1 w-full" placeholder="Min. 8 characters">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#faae33]" for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 w-full" placeholder="Re-enter password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="tiger-button tiger-button-primary">
                        Save Changes ↗
                    </button>
                </form>
            </section>

            <section class="tiger-card h-fit p-6 sm:p-8 space-y-4">
                <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-3">Need Help?</h2>
                <p class="text-xs leading-6 text-[#faae33] opacity-85">
                    Tim layanan pelanggan Daily Books siap membantu pertanyaan terkait kurasi buku, metode pembayaran, atau status pesanan Anda.
                </p>
                <div class="space-y-3 pt-4">
                    <a href="{{ route('chat.index') }}" class="tiger-button tiger-button-primary w-full text-center">
                        Open Customer Care ↗
                    </a>
                    <a href="{{ route('help.index') }}" class="tiger-button tiger-button-ghost w-full text-center">
                        Help & FAQs
                    </a>
                </div>
            </section>
        </div>
    </section>
@endsection
                    </a>
                </div>
            </section>
        </div>
    </section>
@endsection
