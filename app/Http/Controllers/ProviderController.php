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
        $services = auth()->user()->providedServices()->with('category')->latest()->paginate(12);
        return view('provider.manage-services', compact('services'));
    }

    public function manageCategories()
    {
        $this->ensureProvider();
        // Show categories and mapping for provider services
        return view('provider.manage-categories');
    }
}


