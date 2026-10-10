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
        content="@yield('meta_description', 'Stories for every state of mind.')
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body class="min-h-screen antialiased">

    {{-- Announcement Bar --}}
    <div class="border-b border-black bg-[#faa21f] px-4 py-2 text-center text-sm text-black">
        {{ config('storefront.home.announcement') }} &nbsp; Free shipping over Rp{{ number_format(config('storefront.shipping.free_threshold'), 0, ',', '.') }}
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
