<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-brand-base text-brand-text antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="flex flex-col items-center gap-3">
                <a href="/" class="flex items-center gap-2 text-brand-text">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-brand-accent text-brand-text font-semibold">AC</span>
                    <span class="text-lg font-semibold tracking-wide">ArtCore</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-brand-nav/80 border border-brand-card/60 shadow-soft backdrop-blur-sm rounded-xl2">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
