<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 hidden md:hidden">
    <!-- Sidebar replaced this top navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex w-full">
                <!-- Left Sidebar Nav -->
                <div class="hidden sm:block w-64 border-r border-gray-200 mr-6">
                    <div class="h-16 flex items-center px-2">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    </div>
                    <div class="px-2 space-y-1">
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Dashboard</a>
                        @auth
                            @if(Auth::user()->isCustomer())
                                <a href="{{ route('bookings.history') }}" class="block px-3 py-2 rounded {{ request()->routeIs('bookings.history') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">My Bookings</a>
                            @endif

                            @if(Auth::user()->isProvider())
                                <a href="{{ route('provider.services.active') }}" class="block px-3 py-2 rounded {{ request()->routeIs('provider.services.active') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Active Services</a>
                                <a href="{{ route('provider.bookings') }}" class="block px-3 py-2 rounded {{ request()->routeIs('provider.bookings') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Bookings</a>
                                <a href="{{ route('provider.payments') }}" class="block px-3 py-2 rounded {{ request()->routeIs('provider.payments') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Payments</a>
                                <a href="{{ route('provider.chats') }}" class="block px-3 py-2 rounded {{ request()->routeIs('provider.chats') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Chats</a>
                                <a href="{{ route('provider.manage.services') }}" class="block px-3 py-2 rounded {{ request()->routeIs('provider.manage.services') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Manage Services</a>
                                <a href="{{ route('provider.manage.categories') }}" class="block px-3 py-2 rounded {{ request()->routeIs('provider.manage.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Manage Categories</a>
                            @endif

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.users') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Users</a>
                                <a href="{{ route('admin.services') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.services') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Services</a>
                                <a href="{{ route('admin.vendors.pending') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.vendors.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Vendors</a>
                                <a href="{{ route('admin.audit') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.audit') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Audit</a>
                                <a href="{{ route('admin.broadcasts') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.broadcasts') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Broadcasts</a>
                                <a href="{{ route('admin.categories') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Categories</a>
                                <a href="{{ route('admin.commissions') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.commissions') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Commissions</a>
                                <a href="{{ route('admin.gateways') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.gateways') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Gateways</a>
                            @endif
                        @endauth
                    </div>
                </div>
                <!-- Logo -->
                <div class="shrink-0 flex items-center sm:hidden">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if(Auth::user()->isCustomer())
                            <x-nav-link :href="route('bookings.history')" :active="request()->routeIs('bookings.history')">
                                {{ __('My Bookings') }}
                            </x-nav-link>
                            <x-nav-link :href="route('customer.chats')" :active="request()->routeIs('customer.chats')">
                                {{ __('My Chats') }}
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->isProvider())
                            <x-nav-link :href="route('provider.services.active')" :active="request()->routeIs('provider.services.active')">
                                {{ __('Active Services') }}
                            </x-nav-link>
                            <x-nav-link :href="route('provider.bookings')" :active="request()->routeIs('provider.bookings')">
                                {{ __('Bookings') }}
                            </x-nav-link>
                            <x-nav-link :href="route('provider.payments')" :active="request()->routeIs('provider.payments')">
                                {{ __('Payments') }}
                            </x-nav-link>
                            <x-nav-link :href="route('provider.chats')" :active="request()->routeIs('provider.chats')">
                                {{ __('Chats') }}
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.services')" :active="request()->routeIs('admin.services')">
                                {{ __('Services') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.vendors.pending')" :active="request()->routeIs('admin.vendors.*')">
                                {{ __('Vendors') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                                {{ __('Payments') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                                {{ __('Settings') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Notifications and Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="notificationDropdown()">
                    <button @click="toggleDropdown()" 
                            class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out">
                        <i data-feather="bell" class="w-5 h-5"></i>
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount > 99 ? '99+' : unreadCount"
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center min-w-[20px] px-1"></span>
                    </button>

                    <div x-show="isOpen" 
                         @click.away="isOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            <a href="{{ route('notifications.index') }}" 
                               class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                View All
                            </a>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-96 overflow-y-auto">
                            <template x-for="notification in notifications" :key="notification.id">
                                <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200"
                                     :class="{ 'bg-blue-50': !notification.is_read }">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                 :class="notification.is_read ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-600'">
                                                <i :data-feather="getNotificationIcon(notification.type)" class="w-4 h-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900" 
                                               :class="{ 'font-semibold': !notification.is_read }"
                                               x-text="notification.title"></p>
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2" 
                                               x-text="notification.message"></p>
                                            <p class="text-xs text-gray-500 mt-1" 
                                               x-text="notification.created_at"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="notifications.length === 0" class="px-4 py-8 text-center">
                                <i data-feather="bell-off" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                                <p class="text-sm text-gray-500">No notifications</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div x-show="notifications.length > 0" class="px-4 py-3 border-t border-gray-200">
                            <button @click="markAllAsRead()" 
                                    class="w-full text-center text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Mark All as Read
                            </button>
                        </div>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(session('impersonator_id') && !Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('admin.stop-impersonation') }}">
                                @csrf
                                <x-dropdown-link :href="route('admin.stop-impersonation')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Back to Admin') }}
                                </x-dropdown-link>
                            </form>
                            <div class="border-t border-gray-100 my-1"></div>
                        @endif
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @auth
                @if(Auth::user()->isCustomer())
                    <x-responsive-nav-link :href="route('bookings.history')" :active="request()->routeIs('bookings.history')">
                        {{ __('My Bookings') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isProvider())
                    <x-responsive-nav-link :href="route('provider.services.active')" :active="request()->routeIs('provider.services.active')">
                        {{ __('Active Services') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('provider.bookings')" :active="request()->routeIs('provider.bookings')">
                        {{ __('Bookings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('provider.payments')" :active="request()->routeIs('provider.payments')">
                        {{ __('Payments') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('provider.chats')" :active="request()->routeIs('provider.chats')">
                        {{ __('Chats') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.services')" :active="request()->routeIs('admin.services')">
                        {{ __('Services') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.vendors.pending')" :active="request()->routeIs('admin.vendors.*')">
                        {{ __('Vendors') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                        {{ __('Payments') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationDropdown', () => ({
            isOpen: false,
            notifications: [],
            unreadCount: 0,
            
            init() {
                this.loadNotifications();
                this.loadUnreadCount();
                
                // Poll for new notifications every 30 seconds
                setInterval(() => {
                    this.loadUnreadCount();
                    if (this.isOpen) {
                        this.loadNotifications();
                    }
                }, 30000);
            },
            
            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.loadNotifications();
                }
            },
            
            async loadNotifications() {
                try {
                    const response = await fetch('/api/notifications/recent');
                    const data = await response.json();
                    this.notifications = data;
                } catch (error) {
                    console.error('Failed to load notifications:', error);
                }
            },
            
            async loadUnreadCount() {
                try {
                    const response = await fetch('/api/notifications/count');
                    const data = await response.json();
                    this.unreadCount = data.count;
                } catch (error) {
                    console.error('Failed to load unread count:', error);
                }
            },
            
            async markAllAsRead() {
                try {
                    const response = await fetch('/notifications/mark-all-read', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    });
                    
                    if (response.ok) {
                        this.loadNotifications();
                        this.loadUnreadCount();
                    }
                } catch (error) {
                    console.error('Failed to mark all as read:', error);
                }
            },
            
            getNotificationIcon(type) {
                const icons = {
                    'booking_confirmed': 'check-circle',
                    'new_booking': 'calendar',
                    'payment_confirmed': 'credit-card',
                    'payment_received': 'dollar-sign',
                    'booking_reminder': 'clock',
                    'service_completed': 'check-square',
                    'new_review': 'star',
                    'booking_cancelled': 'x-circle',
                    'welcome': 'heart',
                    'provider_verified': 'shield-check',
                };
                return icons[type] || 'bell';
            }
        }))
    });
</script>
@endpush
