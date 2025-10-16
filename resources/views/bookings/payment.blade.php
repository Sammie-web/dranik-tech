<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('home') }}" class="hover:text-black">Home</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <a href="{{ route('services.show', $booking->service) }}" class="hover:text-black">{{ $booking->service->title }}</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-900">Payment</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Methods -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Choose Payment Method</h3>
                            
                            <div class="space-y-4">
                                <!-- Paystack -->
                                <div class="border rounded-lg p-4 cursor-pointer transition-colors duration-200"
                                     :class="{ 'border-black bg-gray-50': selectedMethod === 'paystack', 'border-gray-200': selectedMethod !== 'paystack' }"
                                     @click="selectedMethod = 'paystack'">
                                    <div class="flex items-center">
                         <input type="radio" name="payment_method" value="paystack" class="mr-3 text-black focus:ring-black">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-medium text-gray-900">Paystack</h4>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Recommended</span>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">Pay securely with your debit card, bank transfer, or USSD</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Other gateways removed; only Paystack remains -->

                                <!-- Payment Button -->
                                <div class="pt-6">
                                    <form method="POST" action="{{ route('payments.initialize', $booking) }}">
                                        @csrf

                                        @if($booking->service->price_type === 'negotiable')
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Negotiated Amount (NGN)</label>
                                                <input id="negotiated-amount" type="number" name="negotiated_amount" step="0.01" min="0.01"
                                                       value="{{ old('negotiated_amount', $booking->amount > 0 ? $booking->amount : '') }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent" required>
                                                <p class="text-xs text-gray-500 mt-1">Enter the final agreed amount you will pay the provider. This must be a positive number and less than or equal to the listed price (₦{{ number_format($booking->service->price) }}).</p>
                                            </div>
                                        @else
                                            <input id="negotiated-amount" type="hidden" name="negotiated_amount" value="{{ $booking->amount }}">
                                        @endif

                                        <input type="hidden" name="gateway" value="paystack">

                                        <button id="pay-button" type="submit" class="w-full text-white py-4 rounded-lg font-semibold bg-black hover:bg-gray-800 transition-colors duration-200 flex items-center justify-center">
                                            <i data-feather="lock" class="w-5 h-5 mr-2"></i>
                                            <span id="pay-button-label">Pay ₦{{ number_format($booking->amount) }}</span>
                                        </button>
                                    </form>

                                    <div class="flex items-center justify-center mt-4 text-sm text-gray-500">
                                        <i data-feather="shield" class="w-4 h-4 mr-2"></i>
                                        <span>Your payment is secured with 256-bit SSL encryption</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start">
                            <i data-feather="info" class="w-5 h-5 text-blue-600 mr-3 mt-0.5"></i>
                            <div>
                                <h4 class="font-medium text-blue-900 mb-2">Payment Security</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• All payments are processed securely through certified payment gateways</li>
                                    <li>• Your card details are never stored on our servers</li>
                                    <li>• Full refund available for cancellations made 24+ hours in advance</li>
                                    <li>• Payment is only released to provider after service completion</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h4 class="font-semibold text-gray-900 mb-4">Booking Details</h4>
                            
                            <!-- Service Info -->
                            <div class="flex items-start mb-4">
                                @if($booking->service->images && count($booking->service->images) > 0)
                                    <img src="{{ $booking->service->images[0] }}" alt="{{ $booking->service->title }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <i data-feather="image" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $booking->service->title }}</h5>
                                    <p class="text-sm text-gray-600">{{ $booking->service->category->name }}</p>
                                </div>
                            </div>

                            <!-- Booking Info -->
                            <div class="border-t border-gray-200 pt-4 mb-4 space-y-3">
                                <div class="flex items-center text-sm">
                                    <i data-feather="calendar" class="w-4 h-4 text-gray-400 mr-3"></i>
                                    <span class="text-gray-600">{{ $booking->scheduled_at->format('l, F j, Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i data-feather="clock" class="w-4 h-4 text-gray-400 mr-3"></i>
                                    <span class="text-gray-600">{{ $booking->scheduled_at->format('g:i A') }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i data-feather="user" class="w-4 h-4 text-gray-400 mr-3"></i>
                                    <span class="text-gray-600">{{ $booking->provider->name }}</span>
                                </div>
                                @if($booking->service->is_mobile && $booking->location_details)
                                    <div class="flex items-start text-sm">
                                        <i data-feather="map-pin" class="w-4 h-4 text-gray-400 mr-3 mt-0.5"></i>
                                        <span class="text-gray-600">{{ $booking->location_details['address'] ?? 'Mobile Service' }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Pricing Breakdown -->
                            <div class="border-t border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Service Price</span>
                                    <span id="service-price" class="font-medium">₦{{ number_format($booking->service->price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Platform Fee</span>
                                    <span id="platform-fee" class="font-medium">₦{{ number_format($booking->commission) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between">
                                    <span class="font-semibold text-gray-900">Total Amount</span>
                                    <span id="amount-to-pay" class="font-semibold text-gray-900">₦{{ number_format($booking->amount) }}</span>
                                </div>
                            </div>

                            <!-- Booking Status -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Booking Status</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Booking #{{ $booking->booking_number }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function(){
            // Elements
            const negotiatedEl = document.getElementById('negotiated-amount');
            const payLabelEl = document.getElementById('pay-button-label');
            const amountToPayEl = document.getElementById('amount-to-pay');
            // Service price as a number
            const servicePrice = Number({{ $booking->service->price }});

            function formatCurrency(value) {
                try {
                    return '₦' + new Intl.NumberFormat().format(Number(value).toFixed(2));
                } catch (e) {
                    return '₦' + Number(value).toFixed(2);
                }
            }

            function clamp(n, min, max) {
                if (isNaN(n)) return min;
                return Math.max(min, Math.min(max, n));
            }

            // Elements for breakdown
            const servicePriceEl = document.getElementById('service-price');
            const platformFeeEl = document.getElementById('platform-fee');

            function onNegotiatedInput() {
                if (!negotiatedEl) return;
                let val = parseFloat(negotiatedEl.value);
                if (isNaN(val)) val = 0;
                // Clamp to service price
                if (servicePrice && val > servicePrice) val = servicePrice;

                // Calculate platform commission (5%)
                const commission = +(val * 0.05).toFixed(2);
                const displayVal = val || {{ $booking->amount }};

                // Update display elements
                if (payLabelEl) payLabelEl.textContent = 'Pay ' + formatCurrency(displayVal);
                if (amountToPayEl) amountToPayEl.textContent = formatCurrency(displayVal);
                if (servicePriceEl) servicePriceEl.textContent = formatCurrency(displayVal);
                if (platformFeeEl) platformFeeEl.textContent = formatCurrency(commission);
            }

            if (negotiatedEl) {
                negotiatedEl.addEventListener('input', onNegotiatedInput);
                // Trigger initial formatting
                onNegotiatedInput();
            }
        })();
    </script>
    @endpush
</x-app-layout>
