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

        return view('bookings.create', compact('service', 'availabilities', 'existingBookings'));
    }

    public function store(Request $request, Service $service)
    {
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
                'gateway' => 'pending', // Will be updated when payment is processed
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('bookings.payment', $booking)
                ->with('success', 'Booking created successfully! Please complete your payment.');

        } catch (\Exception $e) {
            DB::rollback();
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
