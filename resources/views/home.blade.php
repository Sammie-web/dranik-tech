@extends('layouts.main')

@section('title', 'D\'RANIK Techs - Digital Service Booking Platform')
@section('description', 'Book trusted services across Africa. Home, beauty, events, healthcare and more. Verified providers, secure payments, seamless booking experience.')

@section('content')
    <!-- Hero Section -->
    {{-- bg-gray-900 --}}
    <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-in">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        {{-- Book Trusted Services --}} Book Trusted Services Anytime,
                        <span class="text-gray-300">Anywhere in Africa</span>
                        {{-- <span class="text-gray-300">Across Africa</span> --}}
                    </h1>
                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        {{-- Book trusted services, anytime, anywhere. --}}
                        Connect with verified service providers for home services, beauty, events, healthcare, and more.
                        Secure payments, guaranteed quality.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('services.index') }}"
                            class="bg-white text-black px-8 py-4 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200 text-center">
                            Find a Service
                        </a>
                        <a href="{{ route('provider.register') }}"
                            class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-black transition-colors duration-200 text-center">
                            Become a Provider
                        </a>
                    </div>
                </div>
                <div class="relative animate-slide-up">
                    <div class="bg-white rounded-2xl p-8 shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">What service do you need?</h3>
                            <i data-feather="search" class="w-6 h-6 text-gray-600"></i>
                        </div>
                        <form action="{{ route('services.index') }}" method="GET" class="space-y-4">
                            <div>
                                <input type="text" name="search" placeholder="What service do you need?"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent text-gray-900">
                            </div>
                            <div>
                                <select name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent text-gray-900">


                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="text" name="location" placeholder="Enter your location"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent text-gray-900">
                            </div>
                            <button type="submit"
                                class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors duration-200">
                                Search Services
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ number_format($stats['total_services']) }}+</div>
                    <div class="text-gray-600">Active Services</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ number_format($stats['total_providers']) }}+</div>
                    <div class="text-gray-600">Verified Providers</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ number_format($stats['total_bookings']) }}+</div>
                    <div class="text-gray-600">Completed Bookings</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ number_format($stats['average_rating'], 1) }}/5</div>
                    <div class="text-gray-600">Average Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Popular Categories</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Discover services across various categories tailored to
                    your needs</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}"
                        class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 border border-gray-100 hover:border-gray-200">
                        <div class="text-center">
                            @if($category->icon)
                                <div
                                    class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-black group-hover:text-white transition-colors duration-200">
                                    <i data-feather="{{ $category->icon }}" class="w-8 h-8"></i>
                                </div>
                            @endif
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $category->services_count ?? 0 }} services</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    View All Categories
                    <i data-feather="arrow-right" class="w-4 h-4 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose D'RANiK Techs (Trust & Security Block) -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Why Choose D’RANiK Techs</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Trust, security and a wide network of verified
                    professionals across Africa.</p>
            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-theme-teal">
                        <i data-feather="check-circle" class="w-6 h-6 text-gray-800"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Verified Vendors</h3>
                        <p class="text-sm text-gray-600">All providers go through verification to ensure quality and trust.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-theme-teal">
                        <i data-feather="shield" class="w-6 h-6 text-gray-800"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Secure Payments</h3>
                        <p class="text-sm text-gray-600">Encrypted payments with trusted gateways and secure checkout flow.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-theme-teal">
                        <i data-feather="headphones" class="w-6 h-6 text-gray-800"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">24/7 Support</h3>
                        <p class="text-sm text-gray-600">Our support team is available around the clock to help with
                            bookings and issues.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-theme-teal">
                        <i data-feather="globe" class="w-6 h-6 text-gray-800"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Pan-African Reach</h3>
                        <p class="text-sm text-gray-600">Find services across multiple countries with a growing network of
                            providers.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Simple steps to get the service you need</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-xl">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Browse & Choose</h3>
                    <p class="text-gray-600">Browse through our verified service providers and choose the one that fits your
                        needs and budget.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-xl">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Book & Pay</h3>
                    <p class="text-gray-600">Select your preferred date and time, make secure payment through our integrated
                        payment gateways.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-xl">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Get Service</h3>
                    <p class="text-gray-600">Enjoy quality service from our professional providers and rate your experience.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Services Section -->
    @if($featuredServices->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Services</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Top-rated services from our verified providers</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredServices as $service)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                            @if($service->images && count($service->images) > 0)
                                <img src="{{ $service->images[0] }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i data-feather="image" class="w-12 h-12 text-gray-400"></i>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">{{ $service->category->name }}</span>
                                    <div class="flex items-center">
                                        <i data-feather="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                        <span
                                            class="text-sm text-gray-600 ml-1">{{ number_format($service->average_rating, 1) }}</span>
                                    </div>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $service->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($service->description, 80) }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="text-lg font-bold text-gray-900">₦{{ number_format($service->price) }}</div>
                                    <a href="{{ route('services.show', $service) }}"
                                        class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition-colors duration-200">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('services.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors duration-200">
                        View All Services
                        <i data-feather="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Reviews Section -->
    @if($recentReviews->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Real reviews from satisfied customers</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentReviews as $review)
                        <div class="bg-white rounded-xl p-6 shadow-sm">
                            <div class="flex items-center mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-feather="star"
                                        class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <p class="text-gray-600 mb-4">"{{ $review->comment }}"</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    @if($review->customer->avatar)
                                        <img src="{{ $review->customer->avatar }}" alt="Avatar"
                                            class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <i data-feather="user" class="w-5 h-5 text-gray-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $review->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->service->title }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 bg-black text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Join thousands of satisfied customers who trust D'RANiK
                Techs for their service needs.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="bg-white text-black px-8 py-4 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200">
                    Sign Up Now
                </a>
                <a href="{{ route('services.index') }}"
                    class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-black transition-colors duration-200">
                    Browse Services
                </a>
            </div>
        </div>
    </section>
@endsection