<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Something went wrong') — {{ config('storefront.brand.name') }}</title>
    <meta name="description" content="{{ config('storefront.brand.name') }} error page">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-[#823513] text-[#faae33] font-sans antialiased selection:bg-[#faae33] selection:text-[#281006]">
    <main class="flex min-h-screen items-center justify-center px-4 py-16 sm:px-6">
        @yield('content')
    </main>
</body>

</html>
