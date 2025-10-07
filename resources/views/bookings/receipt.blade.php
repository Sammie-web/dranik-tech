<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receipt') }} #{{ $booking->booking_number ?? $booking->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Payment Receipt</h3>
                            <p class="text-sm text-gray-500">Date: {{ optional($booking->payment)->created_at?->format('M d, Y H:i') ?? $booking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Booking: #{{ $booking->booking_number ?? $booking->id }}</p>
                            <p class="text-sm text-gray-500">Status: {{ ucfirst(optional($booking->payment)->status ?? 'pending') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Billed To</h4>
                            <p class="text-gray-800">{{ $booking->customer->name }}</p>
                            <p class="text-gray-600 text-sm">{{ $booking->customer->email }}</p>
                            @if($booking->customer->phone)
                                <p class="text-gray-600 text-sm">{{ $booking->customer->phone }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Provider</h4>
                            <p class="text-gray-800">{{ $booking->provider->name }}</p>
                            @if($booking->provider->business_info['name'] ?? false)
                                <p class="text-gray-600 text-sm">{{ $booking->provider->business_info['name'] }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->service->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₦{{ number_format($booking->amount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-end">
                        <div class="w-full md:w-1/2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">₦{{ number_format($booking->amount) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mt-2">
                                <span class="text-gray-600">Commission</span>
                                <span class="text-gray-900">₦{{ number_format($booking->commission) }}</span>
                            </div>
                            <div class="flex items-center justify-between font-semibold mt-4">
                                <span class="text-gray-900">Total</span>
                                <span class="text-gray-900">₦{{ number_format($booking->amount) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('bookings.history') }}" class="text-sm text-blue-600 hover:text-blue-800">Back to history</a>
                        <button onclick="window.print()" class="px-4 py-2 bg-black text-white rounded-lg text-sm hover:bg-gray-800">Print / Save PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



