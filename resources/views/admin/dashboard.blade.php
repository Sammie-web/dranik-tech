
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Welcome & Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Admin Dashboard</h3>
                    <p class="text-gray-600 mt-1">Manage the D'RANIK Techs platform</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users') }}" class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">Manage Users</a>
                    <a href="{{ route('admin.settings') }}" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 transition">Platform Settings</a>
                </div>
            </div>
            <!-- Platform Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-5 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i data-feather="users" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-green-100 text-green-600"><i data-feather="briefcase" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Services</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Service::active()->count() }}</p>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i data-feather="calendar" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Booking::count() }}</p>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i data-feather="dollar-sign" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        @php $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount'); @endphp
                        <p class="text-2xl font-bold text-gray-900">₦{{ number_format($totalRevenue) }}</p>
                    </div>
                </div>
            </div>
            <!-- User Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-base font-semibold text-gray-900">Customers</h3>
                        <i data-feather="user" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-blue-600 mb-1">{{ \App\Models\User::where('role', 'customer')->count() }}</div>
                    <div class="text-sm text-gray-600"><span class="text-green-600">{{ \App\Models\User::where('role', 'customer')->where('is_active', true)->count() }}</span> active</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-base font-semibold text-gray-900">Providers</h3>
                        <i data-feather="briefcase" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-green-600 mb-1">{{ \App\Models\User::where('role', 'provider')->count() }}</div>
                    <div class="text-sm text-gray-600"><span class="text-green-600">{{ \App\Models\User::where('role', 'provider')->where('is_verified', true)->count() }}</span> verified</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-base font-semibold text-gray-900">Admins</h3>
                        <i data-feather="shield" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-purple-600 mb-1">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                    <div class="text-sm text-gray-600">Platform administrators</div>
                </div>
            </div>
            <!-- Activities & Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Recent Activities -->
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Recent Activities</h3>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    @php $recentBookings = \App\Models\Booking::with(['customer', 'provider', 'service'])->latest()->take(5)->get(); @endphp
                    <div class="space-y-3">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg bg-white">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i data-feather="calendar" class="w-5 h-5 text-gray-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">New booking: {{ $booking->service->title }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ $booking->customer->name }} – {{ $booking->provider->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'completed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Popular Categories -->
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Popular Categories</h3>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Manage</a>
                    </div>
                    @php $categories = \App\Models\ServiceCategory::withCount('services')->orderBy('services_count', 'desc')->take(5)->get(); @endphp
                    <div class="space-y-3">
                        @foreach($categories as $category)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-white">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                        @if($category->icon)
                                            <i data-feather="{{ $category->icon }}" class="w-5 h-5 text-gray-600"></i>
                                        @else
                                            <i data-feather="folder" class="w-5 h-5 text-gray-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $category->services_count }} services</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Pending Approvals -->
            <div class="mt-6">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Pending Approvals</h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ \App\Models\User::where('role', 'provider')->where('is_verified', false)->count() }} pending</span>
                    </div>
                    @php $pendingProviders = \App\Models\User::where('role', 'provider')->where('is_verified', false)->latest()->take(5)->get(); @endphp
                    @if($pendingProviders->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pendingProviders as $provider)
                                <div class="p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                            <i data-feather="user" class="w-5 h-5 text-gray-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $provider->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $provider->email }}</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-3">Registered {{ $provider->created_at->diffForHumans() }}</p>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.vendors.review', $provider) }}" class="flex-1 bg-green-600 text-white text-xs px-3 py-2 rounded hover:bg-green-700 transition text-center">Approve</a>
                                        <form action="{{ route('admin.vendors.reject', $provider) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-red-600 text-white text-xs px-3 py-2 rounded hover:bg-red-700 transition">Reject</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i data-feather="check-circle" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-600">No pending approvals</p>
                            <p class="text-sm text-gray-500">All providers are verified</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

