<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('home') }}" class="hover:text-black">Home</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <a href="{{ route('services.index') }}" class="hover:text-black">Services</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <a href="{{ route('services.show', $service) }}" class="hover:text-black">{{ $service->title }}</a>
                <i data-feather="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-900">Book Service</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Schedule Your Service</h3>
                            
                            <form method="POST" action="{{ route('bookings.store', $service) }}" x-data="bookingForm()">
                                @csrf
                                
                                <!-- Date Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Date</label>
                                    <div class="grid grid-cols-7 gap-2 mb-4">
                                        <!-- Day headers -->
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sun</div>
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Mon</div>
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Tue</div>
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Wed</div>
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Thu</div>
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Fri</div>
                                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sat</div>
                                    </div>
                                    
                                    <!-- Calendar Grid -->
                                    <div class="grid grid-cols-7 gap-2" x-data="calendar()">
                                        <template x-for="day in calendarDays" :key="day.date">
                                            <button type="button" 
                                                    @click="selectDate(day)"
                                                    :disabled="!day.available || day.isPast"
                                                    :class="{
                                                        'bg-black text-white': selectedDate === day.date,
                                                        'bg-gray-100 text-gray-400 cursor-not-allowed': !day.available || day.isPast,
                                                        'hover:bg-gray-100': day.available && !day.isPast && selectedDate !== day.date,
                                                        'text-gray-300': !day.inMonth,
                                                        'border-2 border-black': day.isToday && selectedDate !== day.date
                                                    }"
                                                    class="aspect-square flex items-center justify-center text-sm rounded-lg border border-gray-200 transition-colors duration-200">
                                                <span x-text="day.day"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Time Selection -->
                                <div class="mb-6" x-show="selectedDate">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Time</label>
                                    <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                        <template x-for="time in availableTimes" :key="time">
                                            <button type="button"
                                                    @click="selectedTime = time"
                                                    :class="{
                                                        'bg-black text-white': selectedTime === time,
                                                        'border-gray-300 text-gray-700 hover:bg-gray-50': selectedTime !== time
                                                    }"
                                                    class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors duration-200">
                                                <span x-text="time"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Hidden input for scheduled_at -->
                                <input type="hidden" name="scheduled_at" :value="scheduledDateTime">

                                <!-- Location Details (for mobile services) -->
                                @if($service->is_mobile)
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Service Location</label>
                                        <textarea name="location_details[address]" 
                                                  placeholder="Enter your full address where the service should be provided..."
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                                  rows="3" required></textarea>
                                        <p class="text-sm text-gray-500 mt-1">Please provide detailed address including landmarks</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                            <input type="tel" name="location_details[phone]" 
                                                   value="{{ auth()->user()->phone }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                                   required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Alternative Contact</label>
                                            <input type="tel" name="location_details[alt_phone]" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                                        </div>
                                    </div>
                                @endif

                                <!-- Special Instructions -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions (Optional)</label>
                                    <textarea name="customer_notes" 
                                              placeholder="Any special requirements or notes for the service provider..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                              rows="3"></textarea>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-6">
                                    <label class="flex items-start">
                                        <input type="checkbox" required class="mt-1 mr-3 rounded border-gray-300 text-black focus:ring-black">
                                        <span class="text-sm text-gray-600">
                                            I agree to the <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-800">Terms of Service</a> 
                                            and understand the <a href="#" class="text-blue-600 hover:text-blue-800">Cancellation Policy</a>
                                        </span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('services.show', $service) }}" 
                                       class="text-gray-600 hover:text-gray-900">
                                        ← Back to Service
                                    </a>
                                    <button type="submit" 
                                            :disabled="!selectedDate || !selectedTime"
                                            :class="{
                                                'bg-gray-400 cursor-not-allowed': !selectedDate || !selectedTime,
                                                'bg-black hover:bg-gray-800': selectedDate && selectedTime
                                            }"
                                            class="px-8 py-3 text-white rounded-lg font-semibold transition-colors duration-200">
                                        Proceed to Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Service Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h4 class="font-semibold text-gray-900 mb-4">Booking Summary</h4>
                            
                            <!-- Service Info -->
                            <div class="flex items-start mb-4">
                                @if($service->images && count($service->images) > 0)
                                    <img src="{{ $service->images[0] }}" alt="{{ $service->title }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <i data-feather="image" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $service->title }}</h5>
                                    <p class="text-sm text-gray-600">{{ $service->category->name }}</p>
                                    <div class="flex items-center mt-1">
                                        <i data-feather="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                        <span class="text-sm text-gray-600 ml-1">{{ number_format($service->rating, 1) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Provider Info -->
                            <div class="border-t border-gray-200 pt-4 mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        @if($service->provider->avatar)
                                            <img src="{{ $service->provider->avatar }}" alt="Provider" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i data-feather="user" class="w-5 h-5 text-gray-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $service->provider->name }}</p>
                                        @if($service->provider->is_verified)
                                            <p class="text-sm text-green-600 flex items-center">
                                                <i data-feather="check-circle" class="w-4 h-4 mr-1"></i>
                                                Verified Provider
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="border-t border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Service Price</span>
                                    <span class="font-medium">₦{{ number_format($service->price) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Platform Fee (5%)</span>
                                    <span class="font-medium">₦{{ number_format($service->price * 0.05) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between">
                                    <span class="font-semibold text-gray-900">Total</span>
                                    <span class="font-semibold text-gray-900">₦{{ number_format($service->price) }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Platform fee is included in the total price
                                </p>
                            </div>

                            <!-- Service Details -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="space-y-2 text-sm">
                                    @if($service->duration)
                                        <div class="flex items-center text-gray-600">
                                            <i data-feather="clock" class="w-4 h-4 mr-2"></i>
                                            <span>{{ $service->duration }} minutes</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center text-gray-600">
                                        <i data-feather="map-pin" class="w-4 h-4 mr-2"></i>
                                        <span>{{ $service->location }}</span>
                                    </div>
                                    @if($service->is_mobile)
                                        <div class="flex items-center text-green-600">
                                            <i data-feather="truck" class="w-4 h-4 mr-2"></i>
                                            <span>Mobile Service</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                selectedDate: null,
                selectedTime: null,
                
                get scheduledDateTime() {
                    if (!this.selectedDate || !this.selectedTime) return '';
                    return this.selectedDate + ' ' + this.selectedTime;
                }
            }
        }

        function calendar() {
            return {
                selectedDate: null,
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                availableTimes: [],
                
                get calendarDays() {
                    const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                    const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                    const startDate = new Date(firstDay);
                    startDate.setDate(startDate.getDate() - firstDay.getDay());
                    
                    const days = [];
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    // Provider availabilities
                    const availabilities = @json($availabilities);
                    const existingBookings = @json($existingBookings);
                    
                    for (let i = 0; i < 42; i++) {
                        const date = new Date(startDate);
                        date.setDate(startDate.getDate() + i);
                        
                        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
                        const isAvailable = availabilities[dayName] && availabilities[dayName].is_available;
                        const isPast = date < today;
                        const inMonth = date.getMonth() === this.currentMonth;
                        const isToday = date.getTime() === today.getTime();
                        
                        days.push({
                            date: date.toISOString().split('T')[0],
                            day: date.getDate(),
                            available: isAvailable && !isPast && inMonth,
                            isPast: isPast,
                            inMonth: inMonth,
                            isToday: isToday,
                            dayName: dayName
                        });
                    }
                    
                    return days;
                },
                
                selectDate(day) {
                    if (!day.available || day.isPast) return;
                    
                    this.selectedDate = day.date;
                    this.generateAvailableTimes(day.dayName);
                    
                    // Update parent component
                    this.$dispatch('date-selected', { date: day.date });
                },
                
                generateAvailableTimes(dayName) {
                    const availabilities = @json($availabilities);
                    const existingBookings = @json($existingBookings);
                    
                    if (!availabilities[dayName]) {
                        this.availableTimes = [];
                        return;
                    }
                    
                    const availability = availabilities[dayName];
                    const startTime = availability.start_time;
                    const endTime = availability.end_time;
                    
                    // Generate 30-minute slots
                    const times = [];
                    let current = new Date('2000-01-01 ' + startTime);
                    const end = new Date('2000-01-01 ' + endTime);
                    
                    while (current < end) {
                        const timeString = current.toTimeString().slice(0, 5);
                        const dateTimeString = this.selectedDate + ' ' + timeString;
                        
                        // Check if this slot is not already booked
                        if (!existingBookings.includes(dateTimeString)) {
                            times.push(timeString);
                        }
                        
                        current.setMinutes(current.getMinutes() + 30);
                    }
                    
                    this.availableTimes = times;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

