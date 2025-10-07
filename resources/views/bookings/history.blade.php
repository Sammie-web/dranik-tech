<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Service History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">All Bookings</h3>
                    </div>

                    @if($bookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($bookings as $booking)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                            <i data-feather="briefcase" class="w-6 h-6 text-gray-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $booking->service->title }}</h4>
                                            <p class="text-sm text-gray-600">Provider: {{ $booking->provider->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->scheduled_at->format('M d, Y - H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($booking->status === 'completed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">{{ ucfirst($booking->status) }}</span>
                                        <p class="text-sm font-medium text-gray-900 mt-1">₦{{ number_format($booking->amount) }}</p>
                                        <div class="flex items-center space-x-2 mt-2 justify-end">
                                            <a href="{{ route('bookings.show', $booking) }}" class="text-sm text-blue-600 hover:text-blue-800">Details</a>
                                            @if(optional($booking->payment)->status === 'completed')
                                                <a href="{{ route('bookings.receipt', $booking) }}" class="text-sm text-gray-700 hover:text-black">Download Receipt</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i data-feather="calendar" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-600">No bookings yet</p>
                            <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Browse services to get started</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



