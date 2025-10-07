@extends('layouts.main')

@section('title', 'Browse Services - D\'RANiK Techs')
@section('description', 'Discover and book trusted services from verified providers across Africa. Home services, beauty, events, healthcare and more.')

@section('content')
<!-- Hero Section -->
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Browse Services</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Find the perfect service provider for your needs</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('services.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search services..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                    </div>
                    
                    <!-- Category Filter -->
                    <div>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <input type="text" name="location" value="{{ request('location') }}" 
                               placeholder="Location" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                    </div>
                </div>
                
                <!-- Advanced Filters -->
                <div x-data="{ showAdvanced: false }" class="border-t border-gray-200 pt-4">
                    <button type="button" @click="showAdvanced = !showAdvanced" 
                            class="flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                        <span>Advanced Filters</span>
                        <i data-feather="chevron-down" class="w-4 h-4 ml-1" x-bind:class="{ 'rotate-180': showAdvanced }"></i>
                    </button>
                    
                    <div x-show="showAdvanced" x-collapse class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Price (₦)</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   placeholder="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Price (₦)</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   placeholder="1000000" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        </div>
                        
                        <!-- Rating Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Rating</label>
                            <select name="min_rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                                <option value="">Any Rating</option>
                                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                            </select>
                        </div>
                        
                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                        Search Services
                    </button>
                    
                    @if(request()->hasAny(['search', 'category', 'location', 'min_price', 'max_price', 'min_rating', 'sort']))
                        <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if(request()->hasAny(['search', 'category', 'location']))
                        Search Results
                    @else
                        All Services
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">{{ $services->total() }} services found</p>
            </div>
            
            <!-- View Toggle -->
            <div class="flex items-center space-x-2">
                <button class="p-2 text-gray-600 hover:text-black border border-gray-300 rounded-lg">
                    <i data-feather="grid" class="w-5 h-5"></i>
                </button>
                <button class="p-2 text-gray-600 hover:text-black border border-gray-300 rounded-lg">
                    <i data-feather="list" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        @if($services->count() > 0)
            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($services as $service)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden border border-gray-100">
                        <!-- Service Image -->
                        <div class="relative">
                            @if($service->images && count($service->images) > 0)
                                <img src="{{ $service->images[0] }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    @if($service->category->icon)
                                        <i data-feather="{{ $service->category->icon }}" class="w-12 h-12 text-gray-400"></i>
                                    @else
                                        <i data-feather="image" class="w-12 h-12 text-gray-400"></i>
                                    @endif
                                </div>
                            @endif
                            
                            @if($service->is_featured)
                                <div class="absolute top-3 left-3">
                                    <span class="bg-yellow-400 text-black text-xs font-medium px-2 py-1 rounded-full">Featured</span>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 right-3">
                                <button class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50 transition-colors duration-200">
                                    <i data-feather="heart" class="w-4 h-4 text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Service Info -->
                        <div class="p-6">
                            <!-- Category & Rating -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">{{ $service->category->name }}</span>
                                <div class="flex items-center">
                                    <i data-feather="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                    <span class="text-sm text-gray-600 ml-1">{{ number_format($service->rating, 1) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">({{ $service->total_reviews }})</span>
                                </div>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $service->title }}</h3>
                            
                            <!-- Provider -->
                            <div class="flex items-center mb-3">
                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                    @if($service->provider->avatar)
                                        <img src="{{ $service->provider->avatar }}" alt="Provider" class="w-6 h-6 rounded-full object-cover">
                                    @else
                                        <i data-feather="user" class="w-3 h-3 text-gray-600"></i>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-600">{{ $service->provider->name }}</span>
                                @if($service->provider->is_verified)
                                    <i data-feather="check-circle" class="w-4 h-4 text-green-500 ml-1"></i>
                                @endif
                            </div>
                            
                            <!-- Location & Mobile -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-feather="map-pin" class="w-4 h-4 mr-1"></i>
                                    <span>{{ Str::limit($service->location, 20) }}</span>
                                </div>
                                @if($service->is_mobile)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Mobile Service</span>
                                @endif
                            </div>
                            
                            <!-- Price & Book Button -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-gray-900">₦{{ number_format($service->price) }}</span>
                                    @if($service->price_type === 'hourly')
                                        <span class="text-sm text-gray-500">/hour</span>
                                    @elseif($service->price_type === 'negotiable')
                                        <span class="text-sm text-gray-500">negotiable</span>
                                    @endif
                                </div>
                                <a href="{{ route('services.show', $service) }}" 
                                   class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition-colors duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $services->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <i data-feather="search" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No services found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all categories</p>
                <a href="{{ route('services.index') }}" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    View All Services
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Browse by Category</h2>
            <p class="text-xl text-gray-600">Explore services in different categories</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('services.index', ['category' => $category->id]) }}" 
                   class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 text-center">
                    @if($category->icon)
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-black group-hover:text-white transition-colors duration-200">
                            <i data-feather="{{ $category->icon }}" class="w-8 h-8"></i>
                        </div>
                    @endif
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $category->services_count ?? 0 }} services</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection

