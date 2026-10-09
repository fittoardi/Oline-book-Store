<header class="border-b border-black bg-[#f3e5df]">
    <nav class="mx-auto flex min-h-14 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-black" aria-label="Kembang Books home">
            <span aria-hidden="true">+</span><span>KEMBANG BOOKS</span>
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
            <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}" class="arcade-button arcade-button-ghost hidden sm:inline-flex" aria-label="Wishlist">♡</a>
            <a href="{{ route('cart.index') }}" class="arcade-button arcade-button-ghost" aria-label="Shopping cart">CART <span class="arcade-tag ml-1">0</span></a>
            <a href="{{ auth()->check() ? route('profile.index') : route('login') }}" class="arcade-button arcade-button-ghost hidden md:inline-flex" aria-label="Account">ACCOUNT</a>
            <button type="button" class="arcade-button arcade-button-ghost lg:hidden" aria-label="Open menu">|||</button>
        </div>
    </nav>
</header>
