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
                            <!-- Display validation errors and messages -->
                            @if ($errors->any())
                                <div class="mb-4">
                                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                                        <ul class="list-disc pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="mb-4">
                                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('bookings.store', $service) }}" id="booking-form">
                                @csrf
                                
                                <!-- Calendar + Time selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Date</label>

                                    <!-- Small month grid calendar -->
                                    <div id="month-calendar" class="mb-3"></div>

                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Time</label>
                                    <div class="mb-3">
                                        <!-- Time select will be populated based on provider availability and existing bookings -->
                                        <select id="scheduled-time" name="scheduled_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent" required>
                                            <option value="">-- Select a time --</option>
                                        </select>
                                        <p id="times-help" class="text-sm text-gray-500 mt-2">Choose a date from the calendar to load available time slots.</p>
                                        <p id="tz-hint" class="text-xs text-gray-400 mt-1">Times shown in provider timezone.</p>
                                    </div>

                                    <!-- Hidden combined scheduled_at (YYYY-MM-DD HH:MM) populated on submit -->
                                    <input type="hidden" name="scheduled_at" id="scheduled-at" value="" />
                                    <input type="hidden" name="scheduled_date" id="scheduled-date" value="" />
                                </div>

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
                                    <button type="submit" id="booking-submit"
                                            style="background-color: #2ecc71;"
                                            class="px-8 py-3 text-white rounded-lg font-semibold transition-colors duration-200 hover:opacity-90">
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
                                    {{-- Duration removed --}}
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
        // Build availability-aware time slots and compose scheduled_at on submit
        (function() {
            try {
                console.debug('[booking] init script');
            } catch (e) {
                // ignore (console may not be available in some contexts)
            }

            const form = document.getElementById('booking-form');
            if (!form) return;

            const dateInput = document.getElementById('scheduled-date');
            const timeSelect = document.getElementById('scheduled-time');
            const scheduledAtInput = document.getElementById('scheduled-at');
            const submitButton = document.getElementById('booking-submit');
            const timesHelp = document.getElementById('times-help');

            // Data from server
            const availabilities = @json($availabilityMap ?? []);

            const existingBookings = @json($existingBookings);

            // Slot interval and timezone hints
                const SLOT_INTERVAL = Number(@json($slotInterval ?? 30));
                // duration was removed from services; default to 0 for slot calculations
                const duration = 0;
            const PROVIDER_TZ = @json($providerTz ?? config('app.timezone'));
            const CUSTOMER_TZ = @json($customerTz ?? config('app.timezone'));

            // Utility: convert HH:MM -> minutes since midnight
            function timeToMinutes(t) {
                if (!t) return null;
                const [h, m] = t.split(':').map(Number);
                return h * 60 + m;
            }

            function minutesToTime(mins) {
                const h = Math.floor(mins / 60);
                const m = mins % 60;
                return String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0');
            }

            function getDayKeyFromDate(value) {
                // value is YYYY-MM-DD
                const parts = value.split('-');
                if (parts.length !== 3) return null;
                const d = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
                const names = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
                return names[d.getDay()];
            }

            function getBookedTimesForDate(dateValue) {
                // existingBookings entries are 'Y-m-d H:i'
                const set = new Set();
                existingBookings.forEach(function(entry) {
                    if (typeof entry !== 'string') return;
                    if (entry.indexOf(dateValue + ' ') === 0) {
                        const time = entry.split(' ')[1]; // 'HH:MM'
                        set.add(time);
                    }
                });
                return set;
            }

            function populateTimeOptionsForDate(dateValue) {
                // Clear current options except placeholder
                timeSelect.innerHTML = '<option value="">-- Select a time --</option>';

                if (!dateValue) {
                    timeSelect.disabled = true;
                    timesHelp.textContent = 'Choose a date first to load available time slots.';
                    updateButtonState();
                    return;
                }

                const dayKey = getDayKeyFromDate(dateValue);
                const availability = availabilities[dayKey] || null;

                if (!availability || !availability.is_available) {
                    timeSelect.disabled = true;
                    timesHelp.textContent = 'Provider is not available on this day.';
                    updateButtonState();
                    return;
                }

                const startM = timeToMinutes(availability.start_time);
                const endM = timeToMinutes(availability.end_time);
                if (startM === null || endM === null || endM <= startM) {
                    timeSelect.disabled = true;
                    timesHelp.textContent = 'No valid time range configured for this day.';
                    updateButtonState();
                    return;
                }

                const booked = getBookedTimesForDate(dateValue);
                const step = SLOT_INTERVAL; // minutes
                let added = 0;
                    // ensure the entire service duration fits before the availability end
                    for (let m = startM; m + duration <= endM; m += step) {
                    const t = minutesToTime(m);
                    if (booked.has(t)) continue;
                    const opt = document.createElement('option');
                    opt.value = t;
                    opt.textContent = t;
                    timeSelect.appendChild(opt);
                    added++;
                }

                if (added === 0) {
                    timeSelect.disabled = true;
                    timesHelp.textContent = 'No available time slots on this date.';
                } else {
                    timeSelect.disabled = false;
                    timesHelp.textContent = 'Available time slots are shown. Use keyboard to select.';
                }

                updateButtonState();
            }

            function updateButtonState() {
                if (!dateInput || !timeSelect || !submitButton) return;
                const dateVal = dateInput.value.trim();
                const timeVal = timeSelect.value.trim();
                const enabled = dateVal !== '' && timeVal !== '' && !timeSelect.disabled;

                // populate scheduled_at immediately so server-side sees it even if submit handler didn't run
                if (scheduledAtInput) {
                    scheduledAtInput.value = enabled ? (dateVal + ' ' + timeVal) : '';
                }

                submitButton.disabled = !enabled;
                if (enabled) {
                    submitButton.classList.remove('bg-gray-400','cursor-not-allowed');
                    submitButton.classList.add('cursor-pointer');
                } else {
                    submitButton.classList.add('bg-gray-400','cursor-not-allowed');
                    submitButton.classList.remove('cursor-pointer');
                }
            }

            if (dateInput) {
                // set min to today (local date to avoid timezone shift issues)
                const nowLocal = new Date();
                const todayLocal = nowLocal.getFullYear() + '-' + String(nowLocal.getMonth()+1).padStart(2,'0') + '-' + String(nowLocal.getDate()).padStart(2,'0');
                dateInput.setAttribute('min', todayLocal);
                dateInput.addEventListener('change', function() {
                    populateTimeOptionsForDate(dateInput.value);
                });
            }

            if (timeSelect) {
                if (timeSelect) {
                    timeSelect.addEventListener('change', updateButtonState);
                }
            }

            form.addEventListener('submit', function(e) {
                if (!dateInput || !timeSelect || !scheduledAtInput) return;
                if (!dateInput.value || !timeSelect.value) {
                    e.preventDefault();
                    alert('Please select both a date and a time for your booking.');
                    return;
                }

                // Combine into 'YYYY-MM-DD HH:MM' (24-hour)
                const combined = dateInput.value + ' ' + timeSelect.value;
                scheduledAtInput.value = combined;
            });

            // --- Calendar rendering ---
            const calendarRoot = document.getElementById('month-calendar');
            function buildMonthGrid(year, month) {
                // month: 0-11
                calendarRoot.innerHTML = '';

                const header = document.createElement('div');
                header.className = 'flex items-center justify-between mb-2';
                const title = document.createElement('div');
                title.className = 'font-medium';
                title.textContent = new Date(year, month, 1).toLocaleString(undefined, { month: 'long', year: 'numeric' });
                const nav = document.createElement('div');
                nav.innerHTML = '<button id="cal-prev" type="button" class="px-2 py-1 mr-1 border rounded">&lt;</button><button id="cal-next" type="button" class="px-2 py-1 border rounded">&gt;</button>';
                header.appendChild(title);
                header.appendChild(nav);
                calendarRoot.appendChild(header);

                const weekNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                const grid = document.createElement('div');
                grid.className = 'grid grid-cols-7 gap-1 text-center';

                // weekday headers
                weekNames.forEach(w => {
                    const el = document.createElement('div');
                    el.className = 'text-xs text-gray-600';
                    el.textContent = w;
                    grid.appendChild(el);
                });

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // blanks
                for (let i = 0; i < firstDay; i++) {
                    const blank = document.createElement('div');
                    blank.className = 'p-2';
                    grid.appendChild(blank);
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const cell = document.createElement('button');
                    cell.type = 'button';
                    cell.className = 'p-2 rounded hover:bg-gray-100';
                    const dateObj = new Date(year, month, d);
                        // build a local-date YYYY-MM-DD to avoid timezone shifts from toISOString
                        const iso = dateObj.getFullYear() + '-' + String(dateObj.getMonth() + 1).padStart(2, '0') + '-' + String(dateObj.getDate()).padStart(2, '0');
                    cell.dataset.iso = iso;
                    cell.textContent = d;

                    // Disable past dates
                    const nowLocal = new Date();
                    const todayIso = nowLocal.getFullYear() + '-' + String(nowLocal.getMonth() + 1).padStart(2, '0') + '-' + String(nowLocal.getDate()).padStart(2, '0');
                    if (iso < todayIso) {
                        cell.disabled = true;
                        cell.classList.add('text-gray-300');
                    } else {
                        // check provider availability weekday
                        const names = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
                        const dayKey = names[dateObj.getDay()];
                        const availability = availabilities[dayKey] || null;
                        if (!availability || !availability.is_available) {
                            cell.disabled = true;
                            cell.classList.add('text-gray-300','bg-red-50');
                        } else {
                            // clickable
                            cell.addEventListener('click', function() {
                                selectDate(iso);
                            });
                        }
                    }

                    grid.appendChild(cell);
                }

                calendarRoot.appendChild(grid);

                // nav handlers
                document.getElementById('cal-prev').addEventListener('click', function() {
                    const prev = new Date(year, month - 1, 1);
                    buildMonthGrid(prev.getFullYear(), prev.getMonth());
                });
                document.getElementById('cal-next').addEventListener('click', function() {
                    const next = new Date(year, month + 1, 1);
                    buildMonthGrid(next.getFullYear(), next.getMonth());
                });
            }

            function selectDate(iso) {
                // update hidden input and display
                const prevSelected = calendarRoot.querySelector('.bg-black');
                if (prevSelected) prevSelected.classList.remove('bg-black','text-white');
                const btn = calendarRoot.querySelector('[data-iso="' + iso + '"]');
                if (btn) btn.classList.add('bg-black','text-white');
                // propagate to dateInput value for existing logic
                if (dateInput) dateInput.value = iso;
                const scheduledDateHidden = document.getElementById('scheduled-date');
                if (scheduledDateHidden) scheduledDateHidden.value = iso;
                populateTimeOptionsForDate(iso);
            }

            // build initial month grid for current month
            try {
                const now = new Date();
                if (calendarRoot) buildMonthGrid(now.getFullYear(), now.getMonth());
            } catch (err) {
                console.error('[booking] calendar build failed', err);
            }

            // --- keyboard navigation for time select ---
            if (timeSelect) {
                timeSelect.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    const opts = Array.from(timeSelect.options).filter(o => o.value);
                    if (opts.length === 0) return;
                    const idx = opts.findIndex(o => o.value === timeSelect.value);
                    const next = opts[Math.min(opts.length - 1, Math.max(0, idx + 1))];
                    if (next) timeSelect.value = next.value;
                    updateButtonState();
                } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const opts = Array.from(timeSelect.options).filter(o => o.value);
                    if (opts.length === 0) return;
                    const idx = opts.findIndex(o => o.value === timeSelect.value);
                    const prev = opts[Math.max(0, (idx === -1 ? opts.length - 1 : idx) - 1)];
                    if (prev) timeSelect.value = prev.value;
                    updateButtonState();
                }
                });
            }

            // initialize: if user had prefilled values, try to populate times
            if (dateInput && dateInput.value) {
                populateTimeOptionsForDate(dateInput.value);
            } else {
                updateButtonState();
            }
        })();
    </script>
    @endpush
</x-app-layout>

