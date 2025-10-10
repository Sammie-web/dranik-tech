<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Payment;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function ensureProvider()
    {
        if (!auth()->user()->isProvider()) {
            abort(403);
        }
    }

    public function activeServices()
    {
        $this->ensureProvider();
        $services = auth()->user()->providedServices()->where('is_active', true)->latest()->paginate(12);
        return view('provider.active-services', compact('services'));
    }

    public function allBookings()
    {
        $this->ensureProvider();
        $bookings = auth()->user()->providerBookings()->with(['service','customer'])->latest()->paginate(12);
        return view('provider.all-bookings', compact('bookings'));
    }

    public function pendingServices()
    {
        $this->ensureProvider();
        $services = auth()->user()->providedServices()->where('is_active', false)->latest()->paginate(12);
        return view('provider.pending-services', compact('services'));
    }

    public function withdrawals()
    {
        $this->ensureProvider();
        // Placeholder list - integrate with actual payout system later
        $withdrawals = collect();
        return view('provider.withdrawals', compact('withdrawals'));
    }

    public function biodata()
    {
        $this->ensureProvider();
        $user = auth()->user();
        return view('provider.biodata', compact('user'));
    }

    public function updateBiodata(Request $request)
    {
        $this->ensureProvider();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);
        $user = auth()->user();
        $user->update($request->only(['name','phone','address','bio']));
        return back()->with('success','Profile updated');
    }

    public function payments()
    {
        $this->ensureProvider();
        $payments = Payment::where('provider_id', auth()->id())->latest()->paginate(12);
        return view('provider.payments', compact('payments'));
    }

    public function chatList()
    {
        $this->ensureProvider();
        $bookings = auth()->user()->providerBookings()->with(['customer','service'])->latest()->paginate(12);
        return view('provider.chat-list', compact('bookings'));
    }

    public function manageServices()
    {
        $this->ensureProvider();
        $provider = auth()->user();
        $services = $provider->providedServices()->with('category')->latest()->paginate(12);

        // Load provider availabilities to show a quick summary on the services page
        $availabilities = $provider->availabilities()->get()->keyBy('day_of_week');
        $hasAvailability = $availabilities->filter(function($a){ return $a->is_available; })->count() > 0;

        return view('provider.manage-services', compact('services', 'availabilities', 'hasAvailability'));
    }

    public function manageCategories()
    {
        $this->ensureProvider();
        // Show categories and mapping for provider services
        return view('provider.manage-categories');
    }

    public function manageAvailability()
    {
        $this->ensureProvider();
        $provider = auth()->user();
        $availabilities = $provider->availabilities()->get()->keyBy('day_of_week');
        // Days of week order
        $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        return view('provider.manage-availability', compact('availabilities','days'));
    }

    public function updateAvailability(Request $request)
    {
        $this->ensureProvider();
        $provider = auth()->user();

        $data = $request->validate([
            'days' => 'required|array',
            'days.*.day' => 'required|string',
            'days.*.is_available' => 'sometimes|boolean',
            'days.*.start_time' => 'nullable|date_format:H:i',
            'days.*.end_time' => 'nullable|date_format:H:i',
        ]);

        foreach ($data['days'] as $dayItem) {
            $day = $dayItem['day'];
            $availability = $provider->availabilities()->firstOrNew(['day_of_week' => $day]);
            $availability->start_time = $dayItem['start_time'] ?? null;
            $availability->end_time = $dayItem['end_time'] ?? null;
            $availability->is_available = !empty($dayItem['is_available']);
            $availability->provider_id = $provider->id;
            $availability->save();
        }

        return back()->with('success', 'Availability updated');
    }
}


