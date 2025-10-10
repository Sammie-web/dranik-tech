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
                            
                            <div x-data="paymentHandler()" class="space-y-4">
                                <!-- Paystack -->
                                <div class="border rounded-lg p-4 cursor-pointer transition-colors duration-200"
                                     :class="{ 'border-black bg-gray-50': selectedMethod === 'paystack', 'border-gray-200': selectedMethod !== 'paystack' }"
                                     @click="selectedMethod = 'paystack'">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="paystack" 
                                               x-model="selectedMethod" class="mr-3 text-black focus:ring-black">
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

                                <!-- Flutterwave -->
                                <div class="border rounded-lg p-4 cursor-pointer transition-colors duration-200"
                                     :class="{ 'border-black bg-gray-50': selectedMethod === 'flutterwave', 'border-gray-200': selectedMethod !== 'flutterwave' }"
                                     @click="selectedMethod = 'flutterwave'">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" value="flutterwave" 
                                               x-model="selectedMethod" class="mr-3 text-black focus:ring-black">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">Flutterwave</h4>
                                            <p class="text-sm text-gray-600 mt-1">Alternative payment gateway with multiple options</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cash Payment (for mobile services) -->
                                @if($booking->service->is_mobile)
                                    <div class="border rounded-lg p-4 cursor-pointer transition-colors duration-200"
                                         :class="{ 'border-black bg-gray-50': selectedMethod === 'cash', 'border-gray-200': selectedMethod !== 'cash' }"
                                         @click="selectedMethod = 'cash'">
                                        <div class="flex items-center">
                                            <input type="radio" name="payment_method" value="cash" 
                                                   x-model="selectedMethod" class="mr-3 text-black focus:ring-black">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">Pay on Service</h4>
                                                <p class="text-sm text-gray-600 mt-1">Pay cash directly to the service provider</p>
                                                <p class="text-xs text-orange-600 mt-1">⚠️ Booking fee of ₦{{ number_format($booking->commission) }} still applies</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Payment Button -->
                                <div class="pt-6">
                                    <form method="POST" :action="getPaymentAction(selectedMethod)" x-ref="paymentForm" x-on:submit.prevent="handleSubmit($event)">
                                        @csrf
                                        <input type="hidden" name="gateway" :value="selectedMethod">
                                        
                                        <button type="submit" 
                                                :disabled="!selectedMethod"
                                                :class="{
                                                    'bg-gray-400 cursor-not-allowed': !selectedMethod,
                                                    'bg-black hover:bg-gray-800': selectedMethod
                                                }"
                                                class="w-full text-white py-4 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                                            <i data-feather="lock" class="w-5 h-5 mr-2"></i>
                                            <span x-show="selectedMethod === 'cash'">Confirm Booking</span>
                                            <span x-show="selectedMethod !== 'cash'">Pay ₦{{ number_format($booking->amount) }}</span>
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
                                    <span class="font-medium">₦{{ number_format($booking->service->price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Platform Fee</span>
                                    <span class="font-medium">₦{{ number_format($booking->commission) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between">
                                    <span class="font-semibold text-gray-900">Total Amount</span>
                                    <span class="font-semibold text-gray-900">₦{{ number_format($booking->amount) }}</span>
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
        function getPaymentAction(method) {
            // Always initialize payment on the payments.initialize route. Cash payments
            // will be handled by the server-side initialization as well (the form
            // includes gateway=cash). This avoids posting to the current booking URL.
            return '{{ route("payments.initialize", $booking) }}';
        }

        // Handle cash payment confirmation
        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentHandler', () => ({
                selectedMethod: 'paystack',
                
                getPaymentAction(method) {
                    // For the Alpine component we return '#' for cash so that the
                    // submit handler can intercept and ask for confirmation. For
                    // other gateways return the initialize route.
                    if (method === 'cash') {
                        return '#';
                    }
                    return '{{ route("payments.initialize", $booking) }}';
                },
                
                handleSubmit(event) {
                    if (this.selectedMethod === 'cash') {
                        event.preventDefault();
                        
                        if (confirm('Confirm booking with cash payment on service delivery?\n\nNote: A booking fee of ₦{{ number_format($booking->commission) }} will still be charged to your card.')) {
                            // For cash payments, we still need to process the booking fee
                            this.$refs.paymentForm.action = '{{ route("payments.initialize", $booking) }}';
                            this.$refs.paymentForm.submit();
                        }
                    }
                    // For other payment methods, let the form submit normally
                }
            }))
        });
    </script>
    @endpush
</x-app-layout>
