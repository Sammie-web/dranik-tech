@extends('layouts.main')

@section('title', 'About Us - D\'RANIK Techs')
@section('description', 'Learn about D\'RANIK Techs, Africa\'s leading service marketplace connecting customers with verified service providers across multiple categories.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-black">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    About D'RANIK Techs
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Connecting Africa through trusted service relationships. We're building the future of service marketplaces across the continent.
                </p>
            </div>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
                    <p class="text-lg text-gray-700 mb-6">
                        At D'RANIK Techs, we believe everyone deserves access to reliable, high-quality services. Our mission is to create a trusted marketplace that connects customers with verified service providers across Africa, making it easier than ever to find and book the services you need.
                    </p>
                    <p class="text-lg text-gray-700 mb-6">
                        We're not just a platform – we're a community built on trust, quality, and mutual success. Every interaction on our platform is designed to create value for both customers and service providers.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i data-feather="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                            <span class="text-gray-700">Verified Providers</span>
                        </div>
                        <div class="flex items-center">
                            <i data-feather="shield" class="w-5 h-5 text-blue-500 mr-2"></i>
                            <span class="text-gray-700">Secure Payments</span>
                        </div>
                        <div class="flex items-center">
                            <i data-feather="heart" class="w-5 h-5 text-red-500 mr-2"></i>
                            <span class="text-gray-700">Customer First</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Team collaboration" 
                         class="rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -right-6 bg-black text-white p-6 rounded-xl">
                        <div class="text-2xl font-bold">{{ number_format($stats['total_providers']) }}+</div>
                        <div class="text-sm">Verified Providers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Impact</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Since our launch, we've been growing rapidly and making a real difference in communities across Africa.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center bg-white p-8 rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="users" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['total_customers']) }}+</div>
                    <div class="text-gray-600">Happy Customers</div>
                </div>
                
                <div class="text-center bg-white p-8 rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="briefcase" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['total_services']) }}+</div>
                    <div class="text-gray-600">Services Available</div>
                </div>
                
                <div class="text-center bg-white p-8 rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="star" class="w-8 h-8 text-yellow-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['average_rating'], 1) }}</div>
                    <div class="text-gray-600">Average Rating</div>
                </div>
                
                <div class="text-center bg-white p-8 rounded-xl shadow-sm">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="message-circle" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['total_reviews']) }}+</div>
                    <div class="text-gray-600">Reviews & Ratings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Values</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    These core values guide everything we do and shape how we build our platform and community.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-feather="shield-check" class="w-10 h-10 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Trust & Safety</h3>
                    <p class="text-gray-600">
                        We verify every service provider and implement robust safety measures to ensure secure transactions and reliable service delivery.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-feather="zap" class="w-10 h-10 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Innovation</h3>
                    <p class="text-gray-600">
                        We continuously innovate to improve user experience, streamline processes, and introduce new features that benefit our community.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-feather="heart" class="w-10 h-10 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Community</h3>
                    <p class="text-gray-600">
                        We're building more than a platform – we're creating a community where service providers and customers thrive together.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Our diverse team brings together expertise in technology, business, and customer service to create the best possible experience.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="CEO" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">David Ransom</h3>
                        <p class="text-blue-600 mb-3">CEO & Founder</p>
                        <p class="text-gray-600 text-sm">
                            Passionate about connecting communities through technology. 10+ years experience in marketplace platforms.
                        </p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="CTO" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Sarah Johnson</h3>
                        <p class="text-blue-600 mb-3">CTO</p>
                        <p class="text-gray-600 text-sm">
                            Leading our technical vision with expertise in scalable systems and user experience design.
                        </p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Head of Operations" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Michael Brown</h3>
                        <p class="text-blue-600 mb-3">Head of Operations</p>
                        <p class="text-gray-600 text-sm">
                            Ensuring smooth operations and exceptional customer service across all our markets.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Join thousands of customers and service providers who trust D'RANIK Techs for their service needs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('services.index') }}" 
                   class="inline-flex items-center px-8 py-3 bg-white text-black font-semibold rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i data-feather="search" class="w-5 h-5 mr-2"></i>
                    Find Services
                </a>
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-black transition-colors duration-200">
                    <i data-feather="user-plus" class="w-5 h-5 mr-2"></i>
                    Become a Provider
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

