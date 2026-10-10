<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Something went wrong') — {{ config('storefront.brand.name') }}</title>
    <meta name="description" content="{{ config('storefront.brand.name') }} error page">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen antialiased">
    <main class="flex min-h-screen items-center justify-center px-6 py-16">
        @yield('content')
    </main>
</body>

</html>
