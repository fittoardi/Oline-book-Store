<footer class="border-t border-black bg-[#f3e5df]">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <p>© {{ date('Y') }} {{ strtoupper(config('storefront.brand.name')) }}</p>
        <div class="flex gap-5 font-bold uppercase">
            <a href="{{ route('chat.index') }}" class="hover:underline">Customer care</a>
            <a href="{{ route('products.index') }}" class="hover:underline">Shipping</a>
            <a href="{{ route('contact') }}" class="hover:underline">Contact</a>
        </div>
    </div>
</footer>
