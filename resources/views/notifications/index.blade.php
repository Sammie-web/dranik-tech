<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            
            @if($notifications->where('is_read', false)->count() > 0)
                <button onclick="markAllAsRead()" 
                        class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    Mark All as Read
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap items-center gap-4">
                    <!-- Unread Filter -->
                    <div class="flex items-center">
                        <input type="checkbox" id="unread" name="unread" value="1" 
                               {{ request('unread') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-black focus:ring-black focus:ring-offset-0"
                               onchange="this.form.submit()">
                        <label for="unread" class="ml-2 text-sm text-gray-700">Show unread only</label>
                    </div>

                    <!-- Type Filter -->
                    @if($notificationTypes->count() > 0)
                        <div class="flex items-center">
                            <label for="type" class="text-sm text-gray-700 mr-2">Type:</label>
                            <select name="type" id="type" 
                                    class="border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black text-sm"
                                    onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach($notificationTypes as $type)
                                    <option value="{{ $type['value'] }}" 
                                            {{ request('type') === $type['value'] ? 'selected' : '' }}>
                                        {{ $type['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Clear Filters -->
                    @if(request()->hasAny(['unread', 'type']))
                        <a href="{{ route('notifications.index') }}" 
                           class="text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            Clear Filters
                        </a>
                    @endif
                </form>
            </div>

            <!-- Notifications List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200 {{ !$notification->is_read ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}"
                                 x-data="{ showActions: false }">
                                <div class="flex items-start space-x-4">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                    {{ !$notification->is_read ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                                            <i data-feather="{{ \App\Services\NotificationService::getNotificationIcon($notification->type) }}" 
                                               class="w-5 h-5"></i>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-sm font-medium text-gray-900 {{ !$notification->is_read ? 'font-semibold' : '' }}">
                                                    {{ $notification->title }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $notification->message }}
                                                </p>
                                                
                                                <!-- Action Links -->
                                                @if($notification->data)
                                                    <div class="mt-3 flex items-center space-x-4 text-sm">
                                                        @if(isset($notification->data['booking_id']))
                                                            <a href="{{ route('bookings.show', $notification->data['booking_id']) }}" 
                                                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                                View Booking
                                                            </a>
                                                        @endif
                                                        
                                                        @if(isset($notification->data['service_id']))
                                                            <a href="{{ route('services.show', $notification->data['service_id']) }}" 
                                                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                                View Service
                                                            </a>
                                                        @endif
                                                        
                                                        @if(isset($notification->data['review_id']))
                                                            <a href="{{ route('reviews.show', $notification->data['review_id']) }}" 
                                                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                                View Review
                                                            </a>
                                                        @endif
                                                        
                                                        @if(isset($notification->data['can_review']) && $notification->data['can_review'])
                                                            <a href="{{ route('reviews.create', $notification->data['booking_id']) }}" 
                                                               class="text-green-600 hover:text-green-800 transition-colors duration-200">
                                                                Write Review
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center space-x-2 ml-4">
                                                <span class="text-xs text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                                
                                                <div class="relative" @mouseenter="showActions = true" @mouseleave="showActions = false">
                                                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                        <i data-feather="more-horizontal" class="w-4 h-4"></i>
                                                    </button>
                                                    
                                                    <div x-show="showActions" 
                                                         x-transition:enter="transition ease-out duration-100"
                                                         x-transition:enter-start="transform opacity-0 scale-95"
                                                         x-transition:enter-end="transform opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-75"
                                                         x-transition:leave-start="transform opacity-100 scale-100"
                                                         x-transition:leave-end="transform opacity-0 scale-95"
                                                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                        <div class="py-1">
                                                            @if(!$notification->is_read)
                                                                <button onclick="markAsRead({{ $notification->id }})"
                                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                                                    Mark as Read
                                                                </button>
                                                            @endif
                                                            <button onclick="deleteNotification({{ $notification->id }})"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-200">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <i data-feather="bell-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                        <p class="text-gray-500">
                            @if(request('unread'))
                                You have no unread notifications.
                            @elseif(request('type'))
                                No notifications of this type found.
                            @else
                                You don't have any notifications yet.
                            @endif
                        </p>
                        
                        @if(request()->hasAny(['unread', 'type']))
                            <a href="{{ route('notifications.index') }}" 
                               class="inline-flex items-center mt-4 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                View All Notifications
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to mark notification as read');
            });
        }

        function markAllAsRead() {
            if (!confirm('Mark all notifications as read?')) {
                return;
            }

            fetch('/notifications/mark-all-read', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to mark all notifications as read');
            });
        }

        function deleteNotification(notificationId) {
            if (!confirm('Delete this notification?')) {
                return;
            }

            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete notification');
            });
        }
    </script>
    @endpush
</x-app-layout>

