<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="col-span-1 lg:col-span-2">
                <div class="flex items-center space-x-2 mb-4">
                    {{-- <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-black font-bold text-sm">D</span>
                    </div>
                    <span class="text-xl font-bold">D'RANIK Techs</span> --}}
                    @if(!empty($platformSettings['site_logo']))
                        {{-- Larger responsive logo for better visibility --}}
                        <img src="{{ asset('storage/' . $platformSettings['site_logo']) }}" alt="Logo" class="w-[48px] h-16 md:w-[24px] md:h-12  rounded-lg">
                        <span class="text-2xl md:text-3xl font-bold text-white">{{ $platformSettings['site_name'] ?? "D'RANIK Techs" }}</span>
                    @else
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">D</span>
                        </div>
                        <span class="text-2xl md:text-3xl font-bold text-white">D'RANIK Techs</span>
                    @endif
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    Your trusted digital service booking platform connecting customers with verified service providers across Africa. Quality services, trusted professionals, seamless booking.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i data-feather="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i data-feather="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i data-feather="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i data-feather="linkedin" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Home</a></li>
                    <li><a href="{{ route('services.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Browse Services</a></li>
                    <li><a href="{{ route('categories.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Categories</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors duration-200">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Contact</a></li>
                    {{-- <li><a href="{{ route('blog.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Blog</a></li> --}}
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Support</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('help') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Help Center</a></li>
                    <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-white transition-colors duration-200">FAQ</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Terms of Service</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Privacy Policy</a></li>
                    <li><a href="{{ route('provider.register') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Become a Provider</a></li>
                    <li><a href="{{ route('support') }}" class="text-gray-300 hover:text-white transition-colors duration-200">24/7 Support</a></li>
                </ul>
            </div>
        </div>

        <!-- Newsletter Signup -->
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-col lg:flex-row justify-between items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="text-lg font-semibold mb-2">Stay Updated</h3>
                    <p class="text-gray-300">Get the latest updates on new services and special offers.</p>
                </div>
                <div class="flex w-full lg:w-auto">
                    <input type="email" placeholder="Enter your email" class="flex-1 lg:w-64 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent text-white placeholder-gray-400">
                    <button class="px-6 py-2 bg-white text-black rounded-r-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm mb-4 md:mb-0">
                © {{ date('Y') }} D'RANIK Techs. All rights reserved.
            </p>
            <div class="flex items-center space-x-6 text-sm text-gray-400">
                <span class="flex items-center">
                    <i data-feather="shield" class="w-4 h-4 mr-1"></i>
                    Secure Payments
                </span>
                <span class="flex items-center">
                    <i data-feather="check-circle" class="w-4 h-4 mr-1"></i>
                    Verified Providers
                </span>
                <span class="flex items-center">
                    <i data-feather="clock" class="w-4 h-4 mr-1"></i>
                    24/7 Support
                </span>
            </div>
        </div>
    </div>
</footer>

