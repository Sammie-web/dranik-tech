<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    private function ensureAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function manageUsers()
    {
        $this->ensureAdmin();
        $query = request()->input('q');

        $usersQuery = User::query();

        if ($query) {
            $usersQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('role', 'like', "%{$query}%");
            });
        }

        $users = $usersQuery->latest()->paginate(15)->appends(['q' => $query]);
        return view('admin.manage-users', compact('users', 'query'));
    }

    public function destroyUser(User $user)
    {
        $this->ensureAdmin();
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }
        $user->delete();
        return back()->with('success', 'User deleted');
    }

    public function platformSettings()
    {
        $this->ensureAdmin();
        return view('admin.platform-settings');
    }

    public function manageServices()
    {
        $this->ensureAdmin();
        $services = Service::with('provider','category')->latest()->paginate(20);
        return view('admin.manage-services', compact('services'));
    }

    public function audit()
    {
        $this->ensureAdmin();
        return view('admin.audit');
    }

    public function customerPayments()
    {
        $this->ensureAdmin();
        $payments = Payment::latest()->paginate(20);
        return view('admin.customer-payments', compact('payments'));
    }

    public function vendorWithdrawals()
    {
        $this->ensureAdmin();
        $withdrawals = collect();
        return view('admin.vendor-withdrawals', compact('withdrawals'));
    }

    public function loginAs(User $user)
    {
        $this->ensureAdmin();
        // Save impersonator admin id if not already impersonating
        if (!session()->has('impersonator_id')) {
            session(['impersonator_id' => auth()->id()]);
        }
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function stopImpersonation()
    {
        // Do not require current user to be admin; rely on session
        $impersonatorId = session('impersonator_id');
        if ($impersonatorId) {
            session()->forget('impersonator_id');
            $admin = User::find($impersonatorId);
            if ($admin) {
                Auth::login($admin);
                return redirect()->route('admin.users')->with('success', 'Returned to admin account');
            }
        }
        return redirect()->route('admin.users');
    }

    public function editUser(User $user)
    {
        $this->ensureAdmin();
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'role' => 'required|in:customer,provider,admin',
            'is_verified' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
            'is_verified' => (bool)($validated['is_verified'] ?? false),
            'is_active' => (bool)($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated');
    }


    public function pendingVendors()
    {
    $this->ensureAdmin();
    // List all vendors (providers), paginated
    $vendors = User::where('role', 'provider')->latest()->paginate(15);
    return view('admin.pending-vendors', compact('vendors'));
    }

    public function reviewVendor(User $vendor)
    {
    $this->ensureAdmin();
    if ($vendor->role !== 'provider') abort(404);
    $vendor->load('documents');
    return view('admin.review-vendor', compact('vendor'));
    }

    public function approveVendor(User $vendor)
    {
        $this->ensureAdmin();
        if ($vendor->role !== 'provider' || $vendor->is_verified) abort(404);
        $vendor->update(['is_verified' => true]);
        return redirect()->route('admin.dashboard')->with('success', 'Vendor approved');
    }

    public function rejectVendor(User $vendor)
    {
        $this->ensureAdmin();
        if ($vendor->role !== 'provider' || $vendor->is_verified) abort(404);
        $vendor->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Vendor rejected');
    }

    public function verifyVendor(User $vendor)
    {
        $this->ensureAdmin();
        if ($vendor->role !== 'provider') abort(404);
        $vendor->update(['is_verified' => true]);
        return back()->with('success','Vendor verified');
    }

    public function categories()
    {
        $this->ensureAdmin();
        return view('admin.categories');
    }

    public function commissions()
    {
        $this->ensureAdmin();
        return view('admin.commissions');
    }

    public function gateways()
    {
        $this->ensureAdmin();
        return view('admin.gateways');
    }

    public function broadcasts()
    {
        $this->ensureAdmin();
        return view('admin.broadcasts');
    }
}


