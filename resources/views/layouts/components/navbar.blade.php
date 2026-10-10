@php
    $user = auth()->user();
    $cartCount = 0;

    if ($user) {
        $cartCount = \App\Models\CartItem::whereHas('cart', fn ($q) => $q->where('user_id', $user->id)->where('status', 'active'))->sum('quantity');
    } else {
        $cartCount = \App\Models\CartItem::whereHas('cart', fn ($q) => $q->where('session_id', session()->getId())->where('status', 'active'))->sum('quantity');
    }
@endphp
<header class="border-b border-black bg-[#f3e5df]">
    <nav class="mx-auto flex min-h-14 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-black" aria-label="{{ config('storefront.brand.name') }} home">
            <span aria-hidden="true">+</span><span>{{ strtoupper(config('storefront.brand.name')) }}</span>
        </a>

        <div class="hidden items-center gap-6 text-sm font-bold uppercase lg:flex">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <a href="{{ route('about') }}" class="hover:underline">About</a>
            <a href="{{ route('products.index') }}" class="hover:underline">Shop</a>
            <a href="{{ route('categories.index') }}" class="hover:underline">Categories</a>
            <a href="{{ route('reviews.index') }}" class="hover:underline">Reviews</a>
            <a href="{{ route('chat.index') }}" class="hover:underline">Help</a>
        </div>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('wishlist.index') }}" class="arcade-button arcade-button-ghost hidden sm:inline-flex" aria-label="Wishlist">♡</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="arcade-button arcade-button-ghost" aria-label="Shopping cart">CART <span class="arcade-tag ml-1">{{ $cartCount }}</span></a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/admin') }}" class="arcade-button arcade-button-ghost hidden md:inline-flex" aria-label="Admin panel">ADMIN</a>
                @endif
                <a href="{{ route('orders.index') }}" class="arcade-button arcade-button-ghost hidden md:inline-flex" aria-label="Orders">ORDERS</a>
                <form method="POST" action="{{ route('logout') }}" class="hidden md:inline-flex">
                    @csrf
                    <button type="submit" class="arcade-button arcade-button-ghost" aria-label="Logout">LOGOUT</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="arcade-button arcade-button-ghost hidden md:inline-flex" aria-label="Account">LOGIN</a>
            @endauth
            <button type="button" class="arcade-button arcade-button-ghost lg:hidden" aria-label="Open menu">|||</button>
        </div>
    </nav>
</header>
