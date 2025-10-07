<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chats</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Conversations</h3>
                    @if($bookings->count())
                        <div class="space-y-3">
                            @foreach($bookings as $booking)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $booking->service->title }}</p>
                                        <p class="text-sm text-gray-600">Customer: {{ $booking->customer->name }}</p>
                                    </div>
                                    <a href="{{ route('chat.thread', $booking) }}" class="text-sm text-blue-600 hover:text-blue-800">Open Chat</a>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $bookings->links() }}</div>
                    @else
                        <p class="text-gray-600">No conversations yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


