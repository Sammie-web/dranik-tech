<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'D\'RANiK Techs') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>

        <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/sidebar-toggle.js'])
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen flex">
            @include('layouts.sidebar')

            <div class="flex-1 min-h-screen md:ml-0 md:pl-0 md:flex md:justify-center transition-all duration-200">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm border-b border-gray-200">
                        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-6 flex items-center min-h-[56px] sm:min-h-[72px]">
                            <h1 class="text-lg sm:text-xl font-semibold text-gray-800 truncate w-full text-center sm:text-left">{{ $header }}</h1>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="px-3 sm:px-6 py-4 w-full md:max-w-6xl md:mx-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        <!-- Initialize Feather Icons -->
        <script>
            feather.replace();
        </script>
    </body>
</html>
