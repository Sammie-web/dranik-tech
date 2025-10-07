<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('home') }}" class="hover:text-black">Home</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <a href="{{ route('dashboard') }}" class="hover:text-black">Dashboard</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-900">Booking #{{ $booking->booking_number }}</span>
            </nav>

            <!-- Status Alert -->
            @if($booking->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i data-feather="clock" class="w-5 h-5 text-yellow-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-yellow-800">Booking Pending</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                @if($booking->payment && $booking->payment->status === 'completed')
                                    Your booking is confirmed and waiting for provider acceptance.
                                @else
                                    Please complete your payment to confirm this booking.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'confirmed')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i data-feather="check-circle" class="w-5 h-5 text-blue-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-blue-800">Booking Confirmed</h4>
                            <p class="text-sm text-blue-700 mt-1">Your booking has been confirmed by the provider. They will contact you before the scheduled time.</p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'completed')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i data-feather="check-circle" class="w-5 h-5 text-green-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-green-800">Service Completed</h4>
                            <p class="text-sm text-green-700 mt-1">
                                Great! Your service has been completed. 
                                @if(!$booking->review)
                                    <a href="#" class="underline">Leave a review</a> to help other customers.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'cancelled')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i data-feather="x-circle" class="w-5 h-5 text-red-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-red-800">Booking Cancelled</h4>
                            <p class="text-sm text-red-700 mt-1">
                                This booking has been cancelled. 
                                @if($booking->payment && $booking->payment->status === 'refunded')
                                    Your refund has been processed.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Service Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Details</h3>
                            
                            <div class="flex items-start">
                                @if($booking->service->images && count($booking->service->images) > 0)
                                    <img src="{{ $booking->service->images[0] }}" alt="{{ $booking->service->title }}" class="w-20 h-20 object-cover rounded-lg mr-4">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <i data-feather="image" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 mb-1">{{ $booking->service->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">{{ $booking->service->category->name }}</p>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-feather="star" class="w-4 h-4 {{ $i <= $booking->service->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-sm text-gray-600 ml-2">{{ number_format($booking->service->rating, 1) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('services.show', $booking->service) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    View Service
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Provider Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Provider</h3>
                            
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                                    @if($booking->provider->avatar)
                                        <img src="{{ $booking->provider->avatar }}" alt="Provider" class="w-16 h-16 rounded-full object-cover">
                                    @else
                                        <i data-feather="user" class="w-8 h-8 text-gray-600"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $booking->provider->name }}</h4>
                                        @if($booking->provider->is_verified)
                                            <i data-feather="check-circle" class="w-5 h-5 text-green-500 ml-2"></i>
                                        @endif
                                    </div>
                                    <div class="flex items-center mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-feather="star" class="w-4 h-4 {{ $i <= $booking->provider->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-sm text-gray-600 ml-2">{{ number_format($booking->provider->rating, 1) }} ({{ $booking->provider->total_reviews }} reviews)</span>
                                    </div>
                                    @if($booking->provider->phone)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i data-feather="phone" class="w-4 h-4 mr-2"></i>
                                            <span>{{ $booking->provider->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('chat.thread', $booking) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200 text-center">
                                        <i data-feather="message-circle" class="w-4 h-4 mr-1 inline"></i>
                                        Contact Provider
                                    </a>
                                    @if($booking->provider->phone)
                                        <a href="tel:{{ $booking->provider->phone }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors duration-200 text-center">
                                            <i data-feather="phone" class="w-4 h-4 mr-1 inline"></i>
                                            Call
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Timeline</h3>
                            
                            <div class="space-y-4">
                                <!-- Booking Created -->
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                        <i data-feather="plus" class="w-4 h-4 text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Booking Created</p>
                                        <p class="text-sm text-gray-600">{{ $booking->created_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                @if($booking->payment)
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 {{ $booking->payment->status === 'completed' ? 'bg-green-100' : 'bg-yellow-100' }} rounded-full flex items-center justify-center mr-4 mt-1">
                                            <i data-feather="{{ $booking->payment->status === 'completed' ? 'check' : 'clock' }}" class="w-4 h-4 {{ $booking->payment->status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                Payment {{ $booking->payment->status === 'completed' ? 'Completed' : 'Pending' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                @if($booking->payment->paid_at)
                                                    {{ $booking->payment->paid_at->format('M j, Y \a\t g:i A') }}
                                                @else
                                                    Waiting for payment completion
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Service Status -->
                                @if($booking->status === 'confirmed' || $booking->status === 'completed')
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <i data-feather="check-circle" class="w-4 h-4 text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Booking Confirmed</p>
                                            <p class="text-sm text-gray-600">Provider accepted your booking</p>
                                        </div>
                                    </div>
                                @endif

                                @if($booking->status === 'completed')
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <i data-feather="check-circle" class="w-4 h-4 text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Service Completed</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $booking->completed_at ? $booking->completed_at->format('M j, Y \a\t g:i A') : 'Recently completed' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    @if($booking->customer_notes || ($booking->location_details && $booking->service->is_mobile))
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                                
                                @if($booking->customer_notes)
                                    <div class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-2">Special Instructions</h4>
                                        <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $booking->customer_notes }}</p>
                                    </div>
                                @endif

                                @if($booking->location_details && $booking->service->is_mobile)
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Service Location</h4>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-gray-600 mb-2">{{ $booking->location_details['address'] ?? 'Address not provided' }}</p>
                                            @if(isset($booking->location_details['phone']))
                                                <p class="text-sm text-gray-500">Contact: {{ $booking->location_details['phone'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Booking Summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h4 class="font-semibold text-gray-900 mb-4">Booking Summary</h4>
                            
                            <!-- Status -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'completed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <!-- Booking Details -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm">
                                    <i data-feather="hash" class="w-4 h-4 text-gray-400 mr-3"></i>
                                    <span class="text-gray-600">{{ $booking->booking_number }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i data-feather="calendar" class="w-4 h-4 text-gray-400 mr-3"></i>
                                    <span class="text-gray-600">{{ $booking->scheduled_at->format('l, F j, Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i data-feather="clock" class="w-4 h-4 text-gray-400 mr-3"></i>
                                    <span class="text-gray-600">{{ $booking->scheduled_at->format('g:i A') }}</span>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="border-t border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Service Price</span>
                                    <span class="font-medium">₦{{ number_format($booking->service->price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Platform Fee</span>
                                    <span class="font-medium">₦{{ number_format($booking->commission) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between">
                                    <span class="font-semibold text-gray-900">Total Paid</span>
                                    <span class="font-semibold text-gray-900">₦{{ number_format($booking->amount) }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="border-t border-gray-200 pt-4 mt-4 space-y-3">
                                @if($booking->payment && $booking->payment->status === 'pending')
                                    <a href="{{ route('bookings.payment', $booking) }}" 
                                       class="block w-full bg-black text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors duration-200 text-center">
                                        Complete Payment
                                    </a>
                                @endif

                                @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->scheduled_at->diffInHours(now()) >= 24)
                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" 
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full border border-red-300 text-red-700 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors duration-200">
                                            Cancel Booking
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === 'completed' && !$booking->review)
                                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                                        Leave Review
                                    </button>
                                @endif

                                <!-- Provider Actions -->
                                @if(auth()->id() === $booking->provider_id)
                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors duration-200">
                                                Confirm Booking
                                            </button>
                                        </form>
                                    @elseif($booking->status === 'confirmed')
                                        <form method="POST" action="{{ route('bookings.complete', $booking) }}" 
                                              onsubmit="return confirm('Mark this booking as completed?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors duration-200">
                                                Mark as Completed
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

