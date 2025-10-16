<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Dashboard') }}
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
                            <p class="text-gray-600 mt-1">Manage your bookings and discover new services</p>
                        </div>
                        
                    </div>
                    <div class="text-right">
                            <a href="{{ route('services.index') }}" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
                                Browse Services
                            </a>
                        </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i data-feather="calendar" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->customerBookings()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i data-feather="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Completed</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->customerBookings()->where('status', 'completed')->count() }}</p>
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
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->customerBookings()->where('status', 'pending')->count() }}</p>
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
                                <p class="text-sm font-medium text-gray-600">Reviews Given</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->givenReviews()->count() }}</p>
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
                            <div class="space-x-3">
                                <a href="{{ route('customer.chats') }}" class="text-sm text-blue-600 hover:text-blue-800">My Chats</a>
                                <a href="{{ route('bookings.history') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                            </div>
                        </div>
                        
                        @php
                            $recentBookings = auth()->user()->customerBookings()->with(['service', 'provider'])->latest()->take(5)->get();
                        @endphp
                        
                        @if($recentBookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentBookings as $booking)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                <i data-feather="briefcase" class="w-6 h-6 text-gray-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $booking->service->title }}</h4>
                                                <p class="text-sm text-gray-600">{{ $booking->provider->name }}</p>
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
                                <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Browse services to get started</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Favorite Services -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recommended Services</h3>
                            <a href="{{ route('services.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Browse All</a>
                        </div>
                        
                        @php
                            $recommendedServices = \App\Models\Service::active()->featured()->with(['provider', 'category'])->take(4)->get();
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($recommendedServices as $service)
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
                                            <p class="text-sm text-gray-600">{{ $service->provider->name }}</p>
                                            <div class="flex items-center mt-1">
                                                <i data-feather="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                                <span class="text-xs text-gray-500 ml-1">{{ number_format($service->rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">₦{{ number_format($service->price) }}</p>
                                        <a href="{{ route('services.show', $service) }}" class="text-xs text-blue-600 hover:text-blue-800">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

