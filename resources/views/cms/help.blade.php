@extends('layouts.main')

@section('title', 'Help & FAQ - D\'RANIK Techs')
@section('description', 'Find answers to frequently asked questions about using D\'RANIK Techs service marketplace platform.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Help Center
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                    Find answers to common questions and get the help you need to make the most of D'RANIK Techs.
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search for help articles..."
                               class="w-full px-6 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent shadow-sm">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                            <i data-feather="search" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Popular Help Topics</h2>
                <p class="text-lg text-gray-600">Quick access to the most common questions</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="#getting-started" class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors duration-200">
                        <i data-feather="play-circle" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Getting Started</h3>
                    <p class="text-gray-600 text-sm">Learn how to create an account and start using the platform</p>
                </a>
                
                <a href="#booking-services" class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-200 transition-colors duration-200">
                        <i data-feather="calendar" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Booking Services</h3>
                    <p class="text-gray-600 text-sm">Everything about finding and booking services</p>
                </a>
                
                <a href="#for-providers" class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors duration-200">
                        <i data-feather="briefcase" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">For Providers</h3>
                    <p class="text-gray-600 text-sm">Guide for service providers and earning money</p>
                </a>
                
                <a href="#safety-trust" class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition-colors duration-200">
                        <i data-feather="shield" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Safety & Trust</h3>
                    <p class="text-gray-600 text-sm">Learn about our safety measures and policies</p>
                </a>
            </div>
        </div>
    </div>

    <!-- FAQ Sections -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @foreach($faqs as $section)
                <div class="mb-12" id="{{ strtolower(str_replace(' ', '-', $section['category'])) }}">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $section['category'] }}</h2>
                    
                    <div class="space-y-4" x-data="{ openFaq: null }">
                        @foreach($section['questions'] as $index => $faq)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                <button @click="openFaq = openFaq === {{ $loop->parent->index }}_{{ $index }} ? null : {{ $loop->parent->index }}_{{ $index }}"
                                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
                                    <span class="font-medium text-gray-900">{{ $faq['question'] }}</span>
                                    <i data-feather="chevron-down" 
                                       class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                       :class="{ 'rotate-180': openFaq === {{ $loop->parent->index }}_{{ $index }} }"></i>
                                </button>
                                
                                <div x-show="openFaq === {{ $loop->parent->index }}_{{ $index }}"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                     class="px-6 pb-4">
                                    <p class="text-gray-600">{{ $faq['answer'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Contact Support -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Still Need Help?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Can't find what you're looking for? Our support team is here to help you 24/7.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="message-circle" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Live Chat</h3>
                    <p class="text-gray-600 mb-4">Get instant help from our support team</p>
                    <button onclick="openChat()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Start Chat
                    </button>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="mail" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Email Support</h3>
                    <p class="text-gray-600 mb-4">Send us a detailed message</p>
                    <a href="{{ route('contact') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 inline-block">
                        Send Email
                    </a>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="phone" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Phone Support</h3>
                    <p class="text-gray-600 mb-4">Speak directly with our team</p>
                    <a href="tel:+2348012345678" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 inline-block">
                        Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Resources -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Additional Resources</h2>
                <p class="text-lg text-gray-600">More ways to get the help and information you need</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('blog.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-feather="book-open" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Blog & Guides</h3>
                    <p class="text-gray-600 text-sm">In-depth articles and tutorials</p>
                </a>
                
                <a href="{{ route('terms') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-feather="file-text" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Terms of Service</h3>
                    <p class="text-gray-600 text-sm">Platform rules and guidelines</p>
                </a>
                
                <a href="{{ route('privacy') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-feather="shield" class="w-6 h-6 text-teal-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Privacy Policy</h3>
                    <p class="text-gray-600 text-sm">How we protect your information</p>
                </a>
                
                <a href="{{ route('about') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <i data-feather="users" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">About Us</h3>
                    <p class="text-gray-600 text-sm">Learn about our mission and team</p>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openChat() {
        // In a real application, this would open a chat widget
        alert('Live chat feature coming soon! Please use our contact form or email support for immediate assistance.');
    }
</script>
@endpush
@endsection

