@extends('layouts.main')

@section('title', 'Blog - D\'RANIK Techs')
@section('description', 'Read the latest insights, tips, and updates from D\'RANIK Techs about the service marketplace industry.')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    D'RANIK Techs Blog
                </h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Insights, tips, and stories from the world of service marketplaces. Stay updated with the latest trends and best practices.
                </p>
            </div>
        </div>
    </div>

    <!-- Featured Post -->
    @if(count($posts) > 0)
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Article</h2>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="relative h-64 lg:h-auto">
                            <img src="{{ $posts[0]['image'] }}" 
                                 alt="{{ $posts[0]['title'] }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute top-4 left-4">
                                <span class="bg-black text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $posts[0]['category'] }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-8 lg:p-12 flex flex-col justify-center">
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <span>{{ $posts[0]['author'] }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $posts[0]['published_at']->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $posts[0]['read_time'] }}</span>
                            </div>
                            
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                                {{ $posts[0]['title'] }}
                            </h3>
                            
                            <p class="text-gray-600 mb-6 text-lg">
                                {{ $posts[0]['excerpt'] }}
                            </p>
                            
                            <a href="{{ route('blog.post', $posts[0]['id']) }}" 
                               class="inline-flex items-center text-black font-semibold hover:text-gray-700 transition-colors duration-200">
                                Read Full Article
                                <i data-feather="arrow-right" class="w-5 h-5 ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Blog Posts Grid -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Latest Articles</h2>
                
                <!-- Category Filter -->
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Filter by:</span>
                    <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black focus:border-transparent">
                        <option value="">All Categories</option>
                        <option value="home-services">Home Services</option>
                        <option value="industry-insights">Industry Insights</option>
                        <option value="trust-safety">Trust & Safety</option>
                        <option value="provider-tips">Provider Tips</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(array_slice($posts, 1) as $post)
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <div class="relative">
                            <img src="{{ $post['image'] }}" 
                                 alt="{{ $post['title'] }}" 
                                 class="w-full h-48 object-cover">
                            <div class="absolute top-4 left-4">
                                <span class="bg-white text-gray-900 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $post['category'] }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                <span>{{ $post['author'] }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post['published_at']->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post['read_time'] }}</span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                                {{ $post['title'] }}
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $post['excerpt'] }}
                            </p>
                            
                            <a href="{{ route('blog.post', $post['id']) }}" 
                               class="inline-flex items-center text-black font-medium text-sm hover:text-gray-700 transition-colors duration-200">
                                Read More
                                <i data-feather="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-black text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors duration-200">
                    Load More Articles
                </button>
            </div>
        </div>
    </div>

    <!-- Newsletter Signup -->
    <div class="py-16 bg-black">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Stay Updated</h2>
            <p class="text-xl text-gray-300 mb-8">
                Get the latest insights and tips delivered straight to your inbox.
            </p>
            
            <form class="max-w-md mx-auto flex gap-4" x-data="{ email: '' }">
                <input type="email" 
                       x-model="email"
                       placeholder="Enter your email address"
                       class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-white focus:border-transparent">
                <button type="submit" 
                        class="bg-white text-black px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Subscribe
                </button>
            </form>
            
            <p class="text-sm text-gray-400 mt-4">
                No spam, unsubscribe at any time.
            </p>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Explore by Category</h2>
                <p class="text-lg text-gray-600">Find articles that match your interests</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="#" class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i data-feather="home" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Home Services</h3>
                    <p class="text-gray-600 text-sm">Tips and guides for home service providers and customers</p>
                </a>
                
                <a href="#" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-200">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i data-feather="trending-up" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Industry Insights</h3>
                    <p class="text-gray-600 text-sm">Market trends and industry analysis</p>
                </a>
                
                <a href="#" class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:from-purple-100 hover:to-purple-200 transition-all duration-200">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i data-feather="shield" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Trust & Safety</h3>
                    <p class="text-gray-600 text-sm">Security best practices and safety guidelines</p>
                </a>
                
                <a href="#" class="group bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 hover:from-orange-100 hover:to-orange-200 transition-all duration-200">
                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i data-feather="dollar-sign" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Provider Tips</h3>
                    <p class="text-gray-600 text-sm">Strategies to grow your service business</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

