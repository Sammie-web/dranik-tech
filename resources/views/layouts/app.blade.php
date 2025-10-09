<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'D\'RANIK Techs') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>

        <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/sidebar-toggle.js'])
    </head>
    <body class="font-sans antialiased bg-theme-lightGray text-theme-charcoal">
        <div class="min-h-screen flex">
            @include('layouts.sidebar')

            <div class="flex-1 min-h-screen md:ml-0 md:pl-0 md:flex md:justify-center transition-all duration-200">
                <!-- Desktop top navigation (reuses components.navbar) -->
                <div class="hidden md:block md:fixed md:left-64 md:right-0 md:top-0 md:w-[calc(100%-16rem)] z-40">
                    @include('components.navbar')
                </div>
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm border-b border-gray-200">
                        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-6 flex items-center min-h-[56px] sm:min-h-[72px]">
                            <!-- Mobile: hamburger + logo -->
                            <div class="flex items-center md:hidden w-full">
                                <button onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))" class="text-gray-700 mr-3">
                                    <i data-feather="menu" class="w-6 h-6"></i>
                                </button>
                                <a href="{{ route('dashboard') }}" class="flex items-center">
                                    <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                                </a>
                                <div class="flex-1 text-center">
                                    <h1 class="text-lg font-semibold text-gray-800 truncate">{{ $header }}</h1>
                                </div>
                            </div>
                            <!-- Desktop title (kept for larger screens) -->
                            {{-- <div class="hidden md:flex items-center w-full">
                                <h1 class="text-lg sm:text-xl font-semibold text-gray-800 truncate w-full text-center sm:text-left">{{ $header }}</h1>
                            </div> --}}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="px-3 sm:px-6 py-4 w-full md:max-w-6xl md:mx-auto md:mt-8">
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
