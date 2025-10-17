<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', $platformSettings['site_name'] ?? 'D\'RANIK Techs - Your trusted digital service booking platform across Africa')">

    <title>@yield('title', ($platformSettings['site_name'] ?? "D'RANIK Techs") . ' - Digital Service Booking Platform')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Favicon and PWA manifest -->
        @if(!empty($platformSettings['site_favicon']))
            <!-- Use uploaded favicon (stored on public disk) -->
            <link rel="icon" type="image/png" href="{{ asset('storage/' . $platformSettings['site_favicon']) }}">
            <link rel="apple-touch-icon" href="{{ asset('storage/' . $platformSettings['site_favicon']) }}">
        @else
            <!-- Fallback static files in public/ -->
            {{-- <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> --}}
            <!-- Feather Icons -->
            <script src="https://unpkg.com/feather-icons"></script>
            <link rel="apple-touch-icon" href="{{ asset('storage/' . $platformSettings['site_favicon']) }}">
        @endif
        <!-- Web App Manifest for 'Add to Home screen' on mobile -->
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <meta name="theme-color" content="#ffffff">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">

        

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-theme-lightGray text-theme-charcoal">
        <!-- Navigation -->
        @include('components.navbar')

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('components.footer')

        <!-- Initialize Feather Icons -->
        <script>
            feather.replace();
        </script>
        
        @stack('scripts')
    </body>
</html>

