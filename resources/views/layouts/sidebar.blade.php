<aside x-data="{ open: false }" class="z-40 md:fixed md:top-0 md:left-0 md:h-screen md:w-64 md:z-50">
    <!-- Hamburger for mobile -->
    {{-- <div class="md:hidden flex items-center p-5 bg-white border-b border-gray-200">
        <button @click="open = !open" class="text-gray-700 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="ml-4">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div> --}}
    <!-- Sidebar -->
    <div :class="{'block': open, 'hidden': !open}" class="fixed inset-0 bg-black bg-opacity-30 z-30 md:hidden"
        @click="open = false" x-show="open"></div>
    <div :class="{'translate-x-0': open, '-translate-x-full': !open}"
        class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-200 z-40 transform transition-transform duration-200 ease-in-out md:fixed md:top-0 md:left-0 md:h-screen md:w-64 md:z-50 md:translate-x-0 md:block"
        x-show="open || window.innerWidth >= 768">
        <div class="h-16 flex items-center px-4 border-b border-gray-200 md:hidden">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Dashboard</a>
            @auth
                @if(Auth::user()->isCustomer())
                    <a href="{{ route('bookings.history') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('bookings.history') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">My
                        Bookings</a>
                @endif
                @if(Auth::user()->isProvider())
                    <a href="{{ route('provider.services.active') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('provider.services.active') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Active
                        Services</a>
                    <a href="{{ route('provider.bookings') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('provider.bookings') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Bookings</a>
                    <a href="{{ route('provider.payments') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('provider.payments') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Payments</a>
                    <a href="{{ route('provider.chats') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('provider.chats') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Chats</a>
                    <a href="{{ route('provider.manage.services') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('provider.manage.services') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Manage
                        Services</a>
                    <a href="{{ route('provider.manage.categories') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('provider.manage.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Manage
                        Categories</a>
                @endif
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.users') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.users') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Users</a>
                    <a href="{{ route('admin.services') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.services') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Services</a>
                    <a href="{{ route('admin.vendors.pending') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.vendors.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Vendors</a>
                    <a href="{{ route('admin.audit') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.audit') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Audit</a>
                    <a href="{{ route('admin.broadcasts') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.broadcasts') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Broadcasts</a>
                    <a href="{{ route('admin.categories') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Categories</a>
                    <a href="{{ route('admin.commissions') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.commissions') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Commissions</a>
                    <a href="{{ route('admin.gateways') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.gateways') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Gateways</a>
                @endif
            @endauth
        </nav>
        @auth
            <form method="POST" action="{{ route('logout') }}" class="mt-6 px-4">
                @csrf
                <button type="submit"
                    class="w-full block px-3 py-2 mt-2 rounded text-left text-red-700 border border-red-200 hover:bg-red-50 transition">
                    <i data-feather="log-out" class="inline w-4 h-4 mr-2 align-text-bottom"></i> Logout
                </button>
            </form>
        @endauth
    </div>
</aside>
{{-- <div class="h-16 flex items-center px-4 border-b border-gray-200">
    <a href="{{ route('dashboard') }}">
        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
    </a>
</div>
<nav class="p-4 space-y-1">
    <a href="{{ route('dashboard') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Dashboard</a>
    @auth
    @if(Auth::user()->isCustomer())
    <a href="{{ route('bookings.history') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('bookings.history') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">My
        Bookings</a>
    @endif

    @if(Auth::user()->isProvider())
    <a href="{{ route('provider.services.active') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('provider.services.active') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Active
        Services</a>
    <a href="{{ route('provider.bookings') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('provider.bookings') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Bookings</a>
    <a href="{{ route('provider.payments') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('provider.payments') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Payments</a>
    <a href="{{ route('provider.chats') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('provider.chats') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Chats</a>
    <a href="{{ route('provider.manage.services') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('provider.manage.services') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Manage
        Services</a>
    <a href="{{ route('provider.manage.categories') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('provider.manage.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Manage
        Categories</a>
    @endif

    @if(Auth::user()->isAdmin())
    <a href="{{ route('admin.users') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.users') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Users</a>
    <a href="{{ route('admin.services') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.services') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Services</a>
    <a href="{{ route('admin.vendors.pending') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.vendors.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Vendors</a>
    <a href="{{ route('admin.audit') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.audit') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Audit</a>
    <a href="{{ route('admin.broadcasts') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.broadcasts') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Broadcasts</a>
    <a href="{{ route('admin.categories') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Categories</a>
    <a href="{{ route('admin.commissions') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.commissions') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Commissions</a>
    <a href="{{ route('admin.gateways') }}"
        class="block px-3 py-2 rounded {{ request()->routeIs('admin.gateways') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">Gateways</a>
    @endif
    @endauth
</nav> --}}