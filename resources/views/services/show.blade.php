@extends('layouts.main')

@section('title', $service->title . ' - D\'RANiK Techs')
@section('description', Str::limit($service->description, 160))

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
            <a href="{{ route('home') }}" class="hover:text-black">Home</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <a href="{{ route('services.index') }}" class="hover:text-black">Services</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <a href="{{ route('categories.show', $service->category) }}" class="hover:text-black">{{ $service->category->name }}</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-900">{{ $service->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Service Images -->
                <div class="mb-8">
                    @if($service->images && count($service->images) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <img src="{{ $service->images[0] }}" alt="{{ $service->title }}" class="w-full h-64 md:h-96 object-cover rounded-xl">
                            </div>
                            @if(count($service->images) > 1)
                                @foreach(array_slice($service->images, 1, 4) as $image)
                                    <img src="{{ $image }}" alt="{{ $service->title }}" class="w-full h-32 object-cover rounded-lg">
                                @endforeach
                            @endif
                        </div>
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center">
                            @if($service->category->icon)
                                <i data-feather="{{ $service->category->icon }}" class="w-24 h-24 text-gray-400"></i>
                            @else
                                <i data-feather="image" class="w-24 h-24 text-gray-400"></i>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Service Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="flex items-center mb-2">
                                <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">{{ $service->category->name }}</span>
                                @if($service->is_featured)
                                    <span class="bg-yellow-400 text-black text-sm px-3 py-1 rounded-full ml-2">Featured</span>
                                @endif
                                @if($service->is_mobile)
                                    <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full ml-2">Mobile Service</span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $service->title }}</h1>
                            <div class="flex items-center">
                                <div class="flex items-center mr-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-feather="star" class="w-5 h-5 {{ $i <= $service->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-gray-600 ml-2">{{ number_format($service->rating, 1) }} ({{ $service->total_reviews }} reviews)</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i data-feather="map-pin" class="w-4 h-4 mr-1"></i>
                                    <span>{{ $service->location }}</span>
                                </div>
                            </div>
                        </div>
                        <button class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors duration-200">
                            <i data-feather="heart" class="w-5 h-5 text-gray-600"></i>
                        </button>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">About this service</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $service->description }}</p>
                    </div>

                    <!-- Service Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Service Details</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">{{ $service->duration ? $service->duration . ' minutes' : 'Varies' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Price Type:</span>
                                    <span class="font-medium capitalize">{{ $service->price_type }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Mobile Service:</span>
                                    <span class="font-medium">{{ $service->is_mobile ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Total Bookings:</span>
                                    <span class="font-medium">{{ $service->total_bookings }}</span>
                                </div>
                            </div>
                        </div>

                        @if($service->tags && count($service->tags) > 0)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Tags</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($service->tags as $tag)
                                        <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Provider Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">About the Provider</h3>
                    <div class="flex items-start">
                        <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                            @if($service->provider->avatar)
                                <img src="{{ $service->provider->avatar }}" alt="Provider" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <i data-feather="user" class="w-8 h-8 text-gray-600"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $service->provider->name }}</h4>
                                @if($service->provider->is_verified)
                                    <i data-feather="check-circle" class="w-5 h-5 text-green-500 ml-2"></i>
                                    <span class="text-sm text-green-600 ml-1">Verified</span>
                                @endif
                            </div>
                            <div class="flex items-center mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-feather="star" class="w-4 h-4 {{ $i <= $service->provider->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="text-gray-600 ml-2">{{ number_format($service->provider->rating, 1) }} ({{ $service->provider->total_reviews }} reviews)</span>
                            </div>
                            @if($service->provider->bio)
                                <p class="text-gray-600 mb-3">{{ $service->provider->bio }}</p>
                            @endif
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-feather="map-pin" class="w-4 h-4 mr-1"></i>
                                <span>{{ $service->provider->address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                @if($service->reviews->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Customer Reviews</h3>
                            <span class="text-sm text-gray-600">{{ $service->reviews->count() }} reviews</span>
                        </div>

                        <div class="space-y-6">
                            @foreach($service->reviews->take(5) as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                                            @if($review->customer->avatar)
                                                <img src="{{ $review->customer->avatar }}" alt="Customer" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <i data-feather="user" class="w-5 h-5 text-gray-600"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-gray-900">{{ $review->customer->name }}</h4>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i data-feather="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            @if($review->comment)
                                                <p class="text-gray-600">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($service->reviews->count() > 5)
                            <div class="text-center mt-6">
                                <button class="text-blue-600 hover:text-blue-800 font-medium">View All Reviews</button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Booking Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-gray-900 mb-1">₦{{ number_format($service->price) }}</div>
                        @if($service->price_type === 'hourly')
                            <div class="text-gray-600">per hour</div>
                        @elseif($service->price_type === 'negotiable')
                            <div class="text-gray-600">negotiable</div>
                        @endif
                    </div>

                    @auth
                        <a href="{{ route('bookings.create', $service) }}" class="block w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors duration-200 mb-4 text-center">
                            Book Now
                        </a>

                        @php
                            // Determine if the authenticated user already has a booking with this provider
                            $chatBooking = null;
                            if (auth()->check()) {
                                $chatBooking = auth()->user()->customerBookings()->where('provider_id', $service->provider_id)->latest()->first();
                                if (!$chatBooking) {
                                    $chatBooking = $service->bookings()->where('customer_id', auth()->id())->latest()->first();
                                }
                            }
                        @endphp

                        @if(auth()->check() && $chatBooking)
                            <a href="{{ route('chat.thread', $chatBooking) }}" class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200">
                                Contact Provider
                            </a>
                        @else
                            <a href="#" onclick="event.preventDefault(); alert('You need a booking with this provider to start a chat. Please book the service first.');" class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200">
                                Contact Provider
                            </a>
                        @endif

                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors duration-200 text-center mb-4">
                            Sign In to Book
                        </a>
                        <a href="{{ route('register') }}" class="block w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors duration-200 text-center">
                            Create Account
                        </a>
                    @endauth

                    <!-- Quick Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <i data-feather="clock" class="w-4 h-4 text-gray-400 mr-3"></i>
                                <span class="text-gray-600">{{ $service->duration ? $service->duration . ' minutes' : 'Duration varies' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i data-feather="map-pin" class="w-4 h-4 text-gray-400 mr-3"></i>
                                <span class="text-gray-600">{{ $service->location }}</span>
                            </div>
                            @if($service->is_mobile)
                                <div class="flex items-center text-sm">
                                    <i data-feather="truck" class="w-4 h-4 text-green-500 mr-3"></i>
                                    <span class="text-green-600">Mobile service available</span>
                                </div>
                            @endif
                            <div class="flex items-center text-sm">
                                <i data-feather="shield-check" class="w-4 h-4 text-blue-500 mr-3"></i>
                                <span class="text-blue-600">Verified provider</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share -->
                <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Share this service</h4>
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200">
                            Facebook
                        </button>
                        <button class="flex-1 bg-blue-400 text-white py-2 rounded-lg text-sm hover:bg-blue-500 transition-colors duration-200">
                            Twitter
                        </button>
                        <button class="flex-1 bg-green-600 text-white py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-200">
                            WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Customer Reviews</h2>
                <a href="{{ route('reviews.index', $service) }}" 
                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    View All Reviews
                </a>
            </div>

            @if($service->reviews->count() > 0)
                <!-- Rating Summary -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Overall Rating -->
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gray-900 mb-2">{{ number_format($service->rating, 1) }}</div>
                            <x-star-rating :rating="$service->rating" size="xl" :show-number="false" />
                            <p class="text-sm text-gray-600 mt-2">Based on {{ $service->total_reviews }} reviews</p>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="space-y-2">
                            @php
                                $ratingCounts = $service->reviews->groupBy('rating')->map->count();
                            @endphp
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $ratingCounts->get($i, 0);
                                    $percentage = $service->total_reviews > 0 ? ($count / $service->total_reviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-gray-700 w-6">{{ $i }}★</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-8">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @foreach($service->reviews->take(4) as $review)
                        <x-review-card :review="$review" />
                    @endforeach
                </div>

                <!-- Write Review Button (for customers with completed bookings) -->
                @auth
                    @php
                        $completedBooking = auth()->user()->customerBookings()
                            ->where('service_id', $service->id)
                            ->where('status', 'completed')
                            ->whereDoesntHave('review')
                            ->first();
                    @endphp
                    
                    @if($completedBooking)
                        <div class="text-center">
                            <a href="{{ route('reviews.create', $completedBooking) }}" 
                               class="inline-flex items-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                <i data-feather="edit-3" class="w-5 h-5 mr-2"></i>
                                Write a Review
                            </a>
                        </div>
                    @endif
                @endauth
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i data-feather="message-circle" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
                    <p class="text-gray-500 mb-6">Be the first to review this service and help others make informed decisions.</p>
                    
                    @auth
                        @php
                            $completedBooking = auth()->user()->customerBookings()
                                ->where('service_id', $service->id)
                                ->where('status', 'completed')
                                ->whereDoesntHave('review')
                                ->first();
                        @endphp
                        
                        @if($completedBooking)
                            <a href="{{ route('reviews.create', $completedBooking) }}" 
                               class="inline-flex items-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                <i data-feather="edit-3" class="w-5 h-5 mr-2"></i>
                                Write the First Review
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Related Services -->
        @if($relatedServices->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedServices as $relatedService)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                            @if($relatedService->images && count($relatedService->images) > 0)
                                <img src="{{ $relatedService->images[0] }}" alt="{{ $relatedService->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i data-feather="image" class="w-12 h-12 text-gray-400"></i>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $relatedService->title }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ $relatedService->provider->name }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-900">₦{{ number_format($relatedService->price) }}</span>
                                    <a href="{{ route('services.show', $relatedService) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
