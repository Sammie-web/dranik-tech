<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer Payments</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($payments->count())
                        <div class="space-y-3">
                            @foreach($payments as $payment)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                                    <div>
                                        <p class="font-medium text-gray-900">Booking #{{ $payment->booking_id }}</p>
                                        <p class="text-sm text-gray-600">Gateway: {{ ucfirst($payment->gateway) }} · Status: {{ ucfirst($payment->status) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Commission: ₦{{ number_format($payment->commission) }}</p>
                                        <p class="font-medium text-gray-900">Amount: ₦{{ number_format($payment->amount) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $payments->links() }}</div>
                    @else
                        <p class="text-gray-600">No payments found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


