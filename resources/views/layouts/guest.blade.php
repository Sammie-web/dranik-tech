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
    <body class="font-sans antialiased bg-theme-lightGray text-theme-charcoal">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    @if(!empty($platformSettings['site_logo']))
                        {{-- Larger responsive logo for better visibility --}}
                        <img src="{{ asset('storage/' . $platformSettings['site_logo']) }}" alt="Logo" class="w-[48px] h-16 md:w-[24px] md:h-12  rounded-lg">
                    @else
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">D</span>
                        </div>
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
