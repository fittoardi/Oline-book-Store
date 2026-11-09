<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', config('storefront.brand.name'))
    </title>

    <meta
        name="description"
        content="@yield('meta_description', 'Stories for every state of mind.')"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body class="min-h-screen bg-[#823513] text-[#faae33] antialiased selection:bg-[#faae33] selection:text-[#281006]">

    {{-- Announcement Bar — Tiger Gold on Charred Clove with Dotted Rule --}}
    <div class="border-b border-dotted border-[#faae33] bg-[#281006] px-4 py-2.5 text-center text-xs font-semibold tracking-wide text-[#faae33]">
        <span class="uppercase tracking-widest text-[#faae33] font-bold">{{ config('storefront.home.announcement') }}</span>
        <span class="mx-3 opacity-50">•</span>
        <span>Free shipping over Rp{{ number_format(config('storefront.shipping.free_threshold'), 0, ',', '.') }}</span>
    </div>

    {{-- Navbar --}}
    @include('layouts.components.navbar')

    {{-- Main --}}
    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.components.footer')

    @stack('scripts')

</body>

</html>
