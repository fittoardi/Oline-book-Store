@php
    $user = auth()->user();
    $cartCount = 0;

    if ($user) {
        $cartCount = \App\Models\CartItem::whereHas('cart', fn ($q) => $q->where('user_id', $user->id)->where('status', 'active'))->sum('quantity');
    } else {
        $cartCount = \App\Models\CartItem::whereHas('cart', fn ($q) => $q->where('session_id', session()->getId())->where('status', 'active'))->sum('quantity');
    }
@endphp
<header class="border-b border-dotted border-[#faae33] bg-[#281006]" x-data="{ open: false }">
    <nav class="mx-auto flex min-h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-wider text-[#faae33]" aria-label="{{ config('storefront.brand.name') }} home">
            <span class="inline-block h-2 w-2 rounded-full bg-[#faae33]"></span>
            <span class="font-display uppercase tracking-widest">{{ config('storefront.brand.name') }}</span>
        </a>

        <div class="hidden items-center gap-6 text-xs font-semibold uppercase tracking-wider lg:flex">
            <a href="{{ route('home') }}" class="text-[#faae33] transition hover:opacity-75">Home</a>
            <a href="{{ route('about') }}" class="text-[#faae33] transition hover:opacity-75">About</a>
            <a href="{{ route('products.index') }}" class="text-[#faae33] transition hover:opacity-75">Shop</a>
            <a href="{{ route('categories.index') }}" class="text-[#faae33] transition hover:opacity-75">Categories</a>
            <a href="{{ route('collection.index') }}" class="text-[#faae33] transition hover:opacity-75">Collections</a>
            <a href="{{ route('reviews.index') }}" class="text-[#faae33] transition hover:opacity-75">Reviews</a>
            <a href="{{ route('chat.index') }}" class="text-[#faae33] transition hover:opacity-75">Help</a>
        </div>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('wishlist.index') }}" class="tiger-button tiger-button-ghost hidden sm:inline-flex" aria-label="Wishlist">♡</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="tiger-button tiger-button-ghost" aria-label="Shopping cart">
                <span>CART</span>
                <span class="tiger-tag tiger-tag-gold ml-2 !px-2 !py-0.5 !text-[10px]">{{ $cartCount }}</span>
            </a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/admin') }}" class="tiger-button tiger-button-ghost hidden md:inline-flex" aria-label="Admin panel">ADMIN</a>
                @endif
                <a href="{{ route('profile.index') }}" class="tiger-button tiger-button-ghost hidden md:inline-flex" aria-label="Profile">ACCOUNT</a>
                <a href="{{ route('orders.index') }}" class="tiger-button tiger-button-ghost hidden md:inline-flex" aria-label="Orders">ORDERS</a>
                <form method="POST" action="{{ route('logout') }}" class="hidden md:inline-flex">
                    @csrf
                    <button type="submit" class="tiger-button tiger-button-ghost" aria-label="Logout">LOGOUT</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="tiger-button tiger-button-ghost hidden md:inline-flex" aria-label="Account">LOGIN</a>
            @endauth
            <button type="button" @click="open = !open" :aria-expanded="open.toString()" class="tiger-button tiger-button-ghost lg:hidden" aria-label="Toggle navigation menu">MENU</button>
        </div>
    </nav>

    {{-- Mobile Navigation Menu --}}
    <div x-show="open" x-cloak @click.away="open = false" class="border-t border-[#6b2e12] bg-[#402011] px-5 py-5 lg:hidden">
        <div class="flex flex-col space-y-3 text-xs font-bold uppercase tracking-wider">
            <a href="{{ route('home') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Home</a>
            <a href="{{ route('about') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">About</a>
            <a href="{{ route('products.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Shop All Books</a>
            <a href="{{ route('categories.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Genres</a>
            <a href="{{ route('collection.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Curated Collections</a>
            <a href="{{ route('reviews.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Reader Reviews</a>
            <a href="{{ route('chat.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Customer Care</a>
            @auth
                <a href="{{ route('wishlist.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Wishlist</a>
                <a href="{{ route('profile.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">My Profile</a>
                <a href="{{ route('orders.index') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Order History</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/admin') }}" class="border-b border-[#6b2e12] pb-2 text-[#faae33] hover:opacity-75">Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="tiger-button tiger-button-ghost w-full uppercase text-left">Logout</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="tiger-button tiger-button-ghost text-center">Login</a>
                    <a href="{{ route('register') }}" class="tiger-button tiger-button-primary text-center">Register</a>
                </div>
            @endauth
        </div>
    </div>
</header>
