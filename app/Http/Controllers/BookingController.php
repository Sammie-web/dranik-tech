<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create or return a provisional booking for starting a chat/inquiry with the provider.
     * This allows customers to contact providers before making a full booking/payment.
     */
    public function startChat(Service $service)
    {
        $user = auth()->user();

        // Find an existing booking for this customer and provider for this service that can be used for chat
        $booking = Booking::where('customer_id', $user->id)
            ->where('provider_id', $service->provider_id)
            ->where('service_id', $service->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->first();

        if (!$booking) {
            // Create a minimal provisional booking record (no payment) to anchor messages
            $booking = Booking::create([
                'customer_id' => $user->id,
                'provider_id' => $service->provider_id,
                'service_id' => $service->id,
                'scheduled_at' => now(),
                'amount' => 0.00,
                'commission' => 0.00,
                'customer_notes' => 'Provisional inquiry created for chat',
                'status' => 'pending',
            ]);
        }

        return redirect()->route('chat.thread', $booking);
    }

    public function create(Service $service)
    {
        // Get provider availability for the next 30 days
        $availabilities = $service->provider->availabilities()
            ->available()
            ->get()
            ->keyBy('day_of_week');

        // Get existing bookings for the next 30 days
        $existingBookings = Booking::where('provider_id', $service->provider_id)
            ->where('scheduled_at', '>=', now())
            ->where('scheduled_at', '<=', now()->addDays(30))
            ->where('status', '!=', 'cancelled')
            ->pluck('scheduled_at')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d H:i');
            })
            ->toArray();

        // Slot interval (minutes) - allow platform or provider override later
        $slotInterval = config('app.slot_interval', 30);

        // Timezone hints: provider may have timezone attribute, fall back to app timezone
        $providerTz = $service->provider->timezone ?? config('app.timezone');
        $customerTz = auth()->user()->timezone ?? config('app.timezone');

        // Prepare a simple availability map for JS (avoid closures in Blade)
        $weekDays = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];

        // start with defaults (unavailable)
        $availabilityMap = array_fill_keys($weekDays, [
            'is_available' => false,
            'start_time' => null,
            'end_time' => null,
        ]);

        foreach ($availabilities as $a) {
            $day = strtolower($a->day_of_week);
            // ensure time strings are 'HH:MM'
            $start = $a->start_time ? \Carbon\Carbon::parse($a->start_time)->format('H:i') : null;
            $end = $a->end_time ? \Carbon\Carbon::parse($a->end_time)->format('H:i') : null;
            $availabilityMap[$day] = [
                'is_available' => (bool) $a->is_available,
                'start_time' => $start,
                'end_time' => $end,
            ];
        }

    return view('bookings.create', compact('service', 'availabilities', 'existingBookings', 'slotInterval', 'providerTz', 'customerTz', 'availabilityMap'));
    }

    public function store(Request $request, Service $service)
    {
        // If the client sent scheduled_date and scheduled_time separately, merge them into scheduled_at
        if (!$request->filled('scheduled_at') && $request->filled('scheduled_date') && $request->filled('scheduled_time')) {
            $request->merge(['scheduled_at' => $request->input('scheduled_date') . ' ' . $request->input('scheduled_time')]);
        }

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'customer_notes' => 'nullable|string|max:500',
            'location_details' => 'nullable|array',
        ]);

        // Check if the time slot is available
        $scheduledAt = Carbon::parse($request->scheduled_at);
        $existingBooking = Booking::where('provider_id', $service->provider_id)
            ->where('scheduled_at', $scheduledAt)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return back()->withErrors(['scheduled_at' => 'This time slot is not available.']);
        }

        // Check provider availability for this day/time
        $dayOfWeek = strtolower($scheduledAt->format('l'));
        $availability = $service->provider->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return back()->withErrors(['scheduled_at' => 'Provider is not available on this day.']);
        }

        $timeSlot = $scheduledAt->format('H:i');
        if ($timeSlot < $availability->start_time || $timeSlot > $availability->end_time) {
            return back()->withErrors(['scheduled_at' => 'Provider is not available at this time.']);
        }

        DB::beginTransaction();
        try {
            // Calculate commission (5% platform fee)
            $amount = $service->price;
            $commission = $amount * 0.05;

            // Create booking
            $booking = Booking::create([
                'customer_id' => auth()->id(),
                'provider_id' => $service->provider_id,
                'service_id' => $service->id,
                'scheduled_at' => $scheduledAt,
                'amount' => $amount,
                'commission' => $commission,
                'customer_notes' => $request->customer_notes,
                'location_details' => $request->location_details,
                'status' => 'pending',
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'customer_id' => auth()->id(),
                'provider_id' => $service->provider_id,
                'amount' => $amount,
                'commission' => $commission,
                'provider_amount' => $amount - $commission,
                // Use a valid gateway enum placeholder. The actual gateway will be set when the user selects a payment provider.
                // 'pending' is not a valid enum for the `gateway` column and previously caused an insert failure.
                'gateway' => 'paystack',
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('bookings.payment', $booking)
                ->with('success', 'Booking created successfully! Please complete your payment.');

        } catch (\Exception $e) {
            DB::rollback();
            // Log the exception so we have visibility into the root cause instead of only returning a generic message.
            \Log::error('Booking::store error: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null,
                'provider_id' => $service->provider_id ?? null,
                'customer_id' => auth()->id(),
            ]);
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings or if they're the provider/admin
        if (!$this->canViewBooking($booking)) {
            abort(403);
        }

        $booking->load(['service', 'customer', 'provider', 'payment']);
        
        return view('bookings.show', compact('booking'));
    }

    public function payment(Booking $booking)
    {
        // Ensure user can only access payment for their own booking
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->payment && $booking->payment->status === 'completed') {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'Payment has already been completed for this booking.');
        }

        $booking->load(['service', 'provider', 'payment']);
        
        return view('bookings.payment', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if (!$this->canCancelBooking($booking)) {
            abort(403);
        }

        // Can only cancel pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'This booking cannot be cancelled.']);
        }

        // Check if cancellation is within allowed time (24 hours before)
        if ($booking->scheduled_at->diffInHours(now()) < 24) {
            return back()->withErrors(['error' => 'Bookings can only be cancelled 24 hours in advance.']);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Cancelled by ' . (auth()->id() === $booking->customer_id ? 'customer' : 'provider'),
        ]);

        // If payment was completed, initiate refund
        if ($booking->payment && $booking->payment->status === 'completed') {
            $booking->payment->update(['status' => 'refunded']);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function confirm(Booking $booking)
    {
        // Only provider can confirm bookings
        if ($booking->provider_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->withErrors(['error' => 'This booking cannot be confirmed.']);
        }

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking confirmed successfully.');
    }

    public function complete(Booking $booking)
    {
        // Only provider can mark as complete
        if ($booking->provider_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['error' => 'This booking cannot be completed.']);
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update service statistics
        $booking->service->increment('total_bookings');

        return back()->with('success', 'Booking marked as completed.');
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $bookings = $user->customerBookings()
            ->with(['service', 'provider', 'payment'])
            ->latest()
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function receipt(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $booking->load(['service', 'provider', 'customer', 'payment']);
        return view('bookings.receipt', compact('booking'));
    }

    private function canViewBooking(Booking $booking)
    {
        return auth()->id() === $booking->customer_id 
            || auth()->id() === $booking->provider_id 
            || auth()->user()->isAdmin();
    }

    private function canCancelBooking(Booking $booking)
    {
        return auth()->id() === $booking->customer_id 
            || auth()->id() === $booking->provider_id;
    }
}
