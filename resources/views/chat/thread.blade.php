<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat') }} — Booking #{{ $booking->booking_number ?? $booking->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            Service: <span class="text-gray-900">{{ $booking->service->title }}</span>
                        </p>
                        <p class="text-sm text-gray-600">
                            Participants: <span class="text-gray-900">{{ $booking->customer->name }}</span> &middot; <span class="text-gray-900">{{ $booking->provider->name }}</span>
                        </p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 h-96 overflow-y-auto bg-gray-50" id="chatBox">
                        @foreach($messages as $message)
                            <div class="mb-3 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                <div class="inline-block px-3 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-black text-white' : 'bg-white border border-gray-200 text-gray-900' }} max-w-[80%]">
                                    <p class="text-sm">{{ $message->body }}</p>
                                    <p class="text-[10px] mt-1 opacity-70">{{ $message->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('chat.send', $booking) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <input type="text" name="body" class="flex-1 border-gray-300 rounded-lg" placeholder="Type a message..." required maxlength="2000" />
                            <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg text-sm hover:bg-gray-800">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const box = document.getElementById('chatBox');
        if (box) { box.scrollTop = box.scrollHeight; }
    </script>
</x-app-layout>


