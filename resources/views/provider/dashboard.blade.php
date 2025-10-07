<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Provider Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h3>
                            <p class="text-gray-600 mt-1">Manage your services and bookings</p>
                        </div>
                        <div class="flex space-x-3">
                            @if(auth()->user()->is_verified)
                                <a href="#" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                    Add New Service
                                </a>
                                <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    Manage Availability
                                </a>
                            @else
                                <span class="px-6 py-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">Add New Service (Verify to enable)</span>
                                <span class="px-6 py-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">Manage Availability (Verify to enable)</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i data-feather="briefcase" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Active Services</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->providedServices()->active()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i data-feather="calendar" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->providerBookings()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i data-feather="clock" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->providerBookings()->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i data-feather="star" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Rating</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format(auth()->user()->rating, 1) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i data-feather="dollar-sign" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Earnings</p>
                                @php
                                    $totalEarnings = auth()->user()->providerPayments()->where('status', 'completed')->sum('provider_amount');
                                @endphp
                                <p class="text-2xl font-bold text-gray-900">₦{{ number_format($totalEarnings) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        
                        @php
                            $recentBookings = auth()->user()->providerBookings()->with(['service', 'customer'])->latest()->take(5)->get();
                        @endphp
                        
                        @if($recentBookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentBookings as $booking)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                <i data-feather="user" class="w-6 h-6 text-gray-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $booking->service->title }}</h4>
                                                <p class="text-sm text-gray-600">{{ $booking->customer->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $booking->scheduled_at->format('M d, Y - H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($booking->status === 'completed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <p class="text-sm font-medium text-gray-900 mt-1">₦{{ number_format($booking->amount) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i data-feather="calendar" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                <p class="text-gray-600">No bookings yet</p>
                                <p class="text-sm text-gray-500">Start by adding your services</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- My Services -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">My Services</h3>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Manage All</a>
                        </div>
                        
                        @php
                            $myServices = auth()->user()->providedServices()->with('category')->latest()->take(4)->get();
                        @endphp
                        
                        @if($myServices->count() > 0)
                            <div class="space-y-4">
                                @foreach($myServices as $service)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                @if($service->category->icon)
                                                    <i data-feather="{{ $service->category->icon }}" class="w-6 h-6 text-gray-600"></i>
                                                @else
                                                    <i data-feather="briefcase" class="w-6 h-6 text-gray-600"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $service->title }}</h4>
                                                <p class="text-sm text-gray-600">{{ $service->category->name }}</p>
                                                <div class="flex items-center mt-1">
                                                    <i data-feather="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                                    <span class="text-xs text-gray-500 ml-1">{{ number_format($service->rating, 1) }} ({{ $service->total_reviews }} reviews)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">₦{{ number_format($service->price) }}</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i data-feather="briefcase" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                <p class="text-gray-600">No services added yet</p>
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Add your first service</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Reviews</h3>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        
                        @php
                            $recentReviews = auth()->user()->receivedReviews()->with(['customer', 'service'])->latest()->take(3)->get();
                        @endphp
                        
                        @if($recentReviews->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($recentReviews as $review)
                                    <div class="p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center mb-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i data-feather="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 text-sm mb-3">"{{ Str::limit($review->comment, 80) }}"</p>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $review->customer->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $review->service->title }}</p>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i data-feather="star" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                <p class="text-gray-600">No reviews yet</p>
                                <p class="text-sm text-gray-500">Complete more bookings to receive reviews</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

