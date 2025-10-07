@extends('layouts.main')

@section('title', $post['title'] . ' - D\'RANiK Techs Blog')
@section('description', Str::limit($post['content'], 160))

@section('content')
<div class="bg-white">
    <!-- Article Header -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('home') }}" class="hover:text-black">Home</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <a href="{{ route('blog.index') }}" class="hover:text-black">Blog</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-900">{{ $post['title'] }}</span>
            </nav>
            
            <!-- Article Meta -->
            <div class="mb-6">
                <span class="bg-black text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $post['category'] }}
                </span>
            </div>
            
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $post['title'] }}
            </h1>
            
            <!-- Author and Date -->
            <div class="flex items-center space-x-6 text-gray-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                            {{ substr($post['author'], 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $post['author'] }}</p>
                        <p class="text-sm text-gray-500">Author</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 text-sm">
                    <span>{{ $post['published_at']->format('F d, Y') }}</span>
                    <span>•</span>
                    <span>{{ $post['read_time'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Image -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-12">
        <div class="relative">
            <img src="{{ $post['image'] }}" 
                 alt="{{ $post['title'] }}" 
                 class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-2xl">
        </div>
    </div>

    <!-- Article Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-700 leading-relaxed">
                <!-- Sample Article Content -->
                <p class="text-xl text-gray-600 mb-8 font-medium">
                    When it comes to finding reliable home service providers, making the right choice can save you time, money, and stress. Here's our comprehensive guide to help you navigate the process.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-6">1. Define Your Needs Clearly</h2>
                <p class="mb-6">
                    Before you start searching for a service provider, take time to clearly define what you need. Are you looking for a one-time cleaning service or ongoing maintenance? Do you need emergency repairs or can you schedule the work in advance? Having a clear understanding of your requirements will help you find the right provider and communicate your needs effectively.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-6">2. Research and Compare Options</h2>
                <p class="mb-6">
                    Don't settle for the first provider you find. Use platforms like D'RANiK Techs to compare multiple providers in your area. Look at their profiles, services offered, pricing, and availability. This comparison will give you a better understanding of the market and help you make an informed decision.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 my-8">
                    <div class="flex items-start">
                        <i data-feather="info" class="w-6 h-6 text-blue-500 mr-3 mt-1"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Pro Tip</h3>
                            <p class="text-blue-800">
                                Always check if the service provider is verified on the platform. Verified providers have gone through background checks and identity verification for your safety.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-6">3. Check Reviews and Ratings</h2>
                <p class="mb-6">
                    Reviews and ratings from previous customers are invaluable sources of information. Look for providers with consistently high ratings and read through recent reviews to understand their strengths and any potential concerns. Pay attention to how providers respond to negative feedback – this shows their professionalism and commitment to customer satisfaction.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-6">4. Verify Credentials and Insurance</h2>
                <p class="mb-6">
                    Ensure that your chosen provider has the necessary licenses, certifications, and insurance coverage for their services. This protects both you and the provider in case of accidents or damages. Reputable platforms like D'RANiK Techs verify these credentials as part of their provider onboarding process.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-6">5. Communicate Clearly</h2>
                <p class="mb-6">
                    Clear communication is key to a successful service experience. When booking a service, provide detailed information about your requirements, timeline, and any special considerations. Don't hesitate to ask questions about the provider's process, materials they'll use, or cleanup procedures.
                </p>

                <div class="bg-gray-50 rounded-xl p-8 my-12">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Checklist: Before Booking</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i data-feather="check" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span>Provider is verified and has good ratings</span>
                        </li>
                        <li class="flex items-center">
                            <i data-feather="check" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span>Services match your specific needs</span>
                        </li>
                        <li class="flex items-center">
                            <i data-feather="check" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span>Pricing is transparent and reasonable</span>
                        </li>
                        <li class="flex items-center">
                            <i data-feather="check" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span>Provider is available when you need them</span>
                        </li>
                        <li class="flex items-center">
                            <i data-feather="check" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span>You've read recent customer reviews</span>
                        </li>
                    </ul>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mt-12 mb-6">Conclusion</h2>
                <p class="mb-6">
                    Choosing the right home service provider doesn't have to be overwhelming. By following these tips and using trusted platforms like D'RANiK Techs, you can find reliable, professional providers who will deliver quality service. Remember, taking time to research and compare options upfront will save you time and ensure satisfaction with the final results.
                </p>

                <p class="text-lg font-medium text-gray-900 mt-8">
                    Ready to find your perfect service provider? <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 underline">Browse our verified providers</a> and book your next service with confidence.
                </p>
            </div>
        </div>

        <!-- Article Footer -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <!-- Share Buttons -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Share this article</h3>
                    <div class="flex space-x-4">
                        <button class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i data-feather="facebook" class="w-4 h-4 mr-2"></i>
                            Facebook
                        </button>
                        <button class="flex items-center px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors duration-200">
                            <i data-feather="twitter" class="w-4 h-4 mr-2"></i>
                            Twitter
                        </button>
                        <button class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                            <i data-feather="message-circle" class="w-4 h-4 mr-2"></i>
                            WhatsApp
                        </button>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-2">Was this article helpful?</p>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-200">
                            👍 Yes
                        </button>
                        <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200">
                            👎 No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Articles -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Related Articles</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Sample related articles -->
                <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Related article" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-xs font-medium text-blue-600 uppercase tracking-wide">Industry Insights</span>
                        <h3 class="text-lg font-semibold text-gray-900 mt-2 mb-3">The Future of Service Marketplaces</h3>
                        <p class="text-gray-600 text-sm mb-4">Exploring trends shaping the service industry...</p>
                        <a href="#" class="text-black font-medium text-sm hover:text-gray-700">Read More →</a>
                    </div>
                </article>
                
                <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Related article" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-xs font-medium text-green-600 uppercase tracking-wide">Trust & Safety</span>
                        <h3 class="text-lg font-semibold text-gray-900 mt-2 mb-3">Building Trust in Digital Services</h3>
                        <p class="text-gray-600 text-sm mb-4">How verification systems create safer marketplaces...</p>
                        <a href="#" class="text-black font-medium text-sm hover:text-gray-700">Read More →</a>
                    </div>
                </article>
                
                <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         alt="Related article" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-xs font-medium text-purple-600 uppercase tracking-wide">Provider Tips</span>
                        <h3 class="text-lg font-semibold text-gray-900 mt-2 mb-3">Maximizing Your Earnings</h3>
                        <p class="text-gray-600 text-sm mb-4">Strategies for service providers to grow their business...</p>
                        <a href="#" class="text-black font-medium text-sm hover:text-gray-700">Read More →</a>
                    </div>
                </article>
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('blog.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    <i data-feather="arrow-left" class="w-5 h-5 mr-2"></i>
                    Back to Blog
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

