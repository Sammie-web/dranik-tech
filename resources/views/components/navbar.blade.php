<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    @if(!empty($platformSettings['site_logo']))
                        {{-- Larger responsive logo for better visibility --}}
                        <img src="{{ asset('storage/' . $platformSettings['site_logo']) }}" alt="Logo" class="w-[48px] h-16 md:w-[24px] md:h-12  rounded-lg">
                        <span class="text-2xl md:text-3xl font-bold text-gray-900">{{ $platformSettings['site_name'] ?? "D'RANIK Techs" }}</span>
                    @else
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">D</span>
                        </div>
                        <span class="text-2xl md:text-3xl font-bold text-gray-900">D'RANIK Techs</span>
                    @endif
                </a>
            </div>

            <!-- Mobile hamburger (shows on mobile only) - visible only on public/non-dashboard pages -->
            @php
                $route = Route::currentRouteName() ?? '';
            @endphp
            @if(Str::startsWith($route, ['dashboard', 'customer.', 'provider.', 'admin.']))
                <div class="md:hidden ml-3">
                    <button onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))" class="text-gray-700 hover:text-black">
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            @endif

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-black transition-colors duration-200">Home</a>
                <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-black transition-colors duration-200">Services</a>
                <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-black transition-colors duration-200">Categories</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-black transition-colors duration-200">About</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-black transition-colors duration-200">Contact</a>
            </div>

            <!-- Desktop Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 text-gray-700 hover:text-black transition-colors duration-200">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <i data-feather="user" class="w-4 h-4"></i>
                                @endif
                            </div>
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                            <i data-feather="chevron-down" class="w-4 h-4"></i>
                        </button>
                        
                        <div x-show="dropdownOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="dropdownOpen = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            {{-- <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a> --}}
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            @if(auth()->user()->isProvider())
                                <a href="{{ route('provider.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Provider Dashboard</a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                            @endif
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-black transition-colors duration-200">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors duration-200">Get Started</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-black">
                    <i data-feather="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="md:hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Home</a>
            <a href="{{ route('services.index') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Services</a>
            <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Categories</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Contact</a>
        </div>
        
        @auth
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-5">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <i data-feather="user" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Profile</a>
                    @if(auth()->user()->isProvider())
                        <a href="{{ route('provider.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Provider Dashboard</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Admin Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Sign Out</button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="space-y-1 px-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-black hover:bg-gray-50 rounded-md">Sign In</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 bg-black text-white rounded-md hover:bg-gray-800">Get Started</a>
                </div>
            </div>
        @endauth
    </div>
</nav>

