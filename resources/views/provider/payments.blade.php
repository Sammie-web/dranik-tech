<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payments</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payments for your services</h3>
                    @if($payments->count())
                        <div class="space-y-3">
                            @foreach($payments as $payment)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                                    <div>
                                        <p class="text-sm text-gray-600">Booking #{{ $payment->booking_id }}</p>
                                        <p class="text-xs text-gray-500">Status: {{ ucfirst($payment->status) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">₦{{ number_format($payment->provider_amount) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $payments->links() }}</div>
                    @else
                        <p class="text-gray-600">No payments yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


