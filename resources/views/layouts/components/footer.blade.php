<footer class="border-t border-dotted border-[#faae33] bg-[#281006]">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 text-xs font-semibold sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <p class="uppercase tracking-widest text-[#faae33]">© {{ date('Y') }} {{ config('storefront.brand.name') }}</p>
        <div class="flex gap-6 uppercase tracking-wider">
            <a href="{{ route('chat.index') }}" class="text-[#faae33] transition hover:opacity-75">Customer care</a>
            <a href="{{ route('products.index') }}" class="text-[#faae33] transition hover:opacity-75">Shipping</a>
            <a href="{{ route('contact') }}" class="text-[#faae33] transition hover:opacity-75">Contact</a>
        </div>
    </div>
</footer>
