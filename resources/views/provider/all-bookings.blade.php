<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Bookings</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Bookings</h3>
                    @if($bookings->count())
                        <div class="space-y-4">
                            @foreach($bookings as $booking)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $booking->service->title }}</p>
                                        <p class="text-sm text-gray-600">Customer: {{ $booking->customer->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ ucfirst($booking->status) }}</span>
                                        <p class="text-sm font-medium text-gray-900 mt-1">₦{{ number_format($booking->amount) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $bookings->links() }}</div>
                    @else
                        <p class="text-gray-600">No bookings found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


