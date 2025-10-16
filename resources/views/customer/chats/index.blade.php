<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Chats</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-gray-600">Below are your recent conversations with providers. Click any thread to continue the chat.</p>
                        <div class="mt-2 text-xs text-gray-500">Showing <strong>{{ $bookings->total() }}</strong> threads</div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div>
                            <input id="chat-search" name="q" value="{{ $search ?? '' }}" placeholder="Search by service or provider" class="border px-3 py-2 rounded-l-lg text-sm" />
                            <button id="chat-search-btn" class="bg-black text-white px-3 py-2 rounded-r-lg text-sm">Search</button>
                        </div>
                        <div>
                            <select id="chat-sort" class="border px-3 py-2 rounded text-sm">
                                <option value="recent" {{ (isset($sort) && $sort === 'recent') ? 'selected' : '' }}>Most Recent</option>
                                <option value="unread" {{ (isset($sort) && $sort === 'unread') ? 'selected' : '' }}>Unread First</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if($bookings->isEmpty())
                    <div class="text-center py-8">
                        <i data-feather="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-600">No conversations yet.</p>
                        <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Browse services to start a conversation</a>
                    </div>
                @else
                    <div id="chat-list" class="space-y-4">
                        @foreach($bookings as $booking)
                            <a href="{{ route('chat.thread', $booking) }}" class="block p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                            <i data-feather="user" class="w-5 h-5 text-gray-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $booking->service->title }}</h4>
                                            <p class="text-sm text-gray-600">With: {{ $booking->provider->name }}</p>
                                            @if($booking->lastMessage)
                                                <p class="text-xs text-gray-500">{{ Str::limit($booking->lastMessage->body, 80) }} · <span class="text-xs text-gray-400">{{ $booking->lastMessage->created_at->diffForHumans() }}</span></p>
                                            @elseif($booking->scheduled_at)
                                                <p class="text-xs text-gray-500">{{ $booking->scheduled_at->format('M d, Y - H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="flex items-center space-x-3">
                                            @if(isset($booking->unread_count) && $booking->unread_count > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">{{ $booking->unread_count }}</span>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800 @elseif($booking->status === 'completed') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">{{ ucfirst($booking->status) }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 mt-1">₦{{ number_format($booking->amount) }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    (function(){
        const searchInput = document.getElementById('chat-search');
        const searchBtn = document.getElementById('chat-search-btn');
        const sortSelect = document.getElementById('chat-sort');
        const listRoot = document.getElementById('chat-list');

        function buildItemHtml(b) {
            const title = escapeHtml(b.service.title || 'Service');
            const provider = escapeHtml(b.provider.name || 'Provider');
            const last = b.lastMessage ? (escapeHtml(b.lastMessage.body.substring(0,80)) + ' · ' + timeAgo(b.lastMessage.created_at)) : '';
            const unread = b.unread_count && b.unread_count > 0 ? ('<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">'+b.unread_count+'</span>') : '';
            const statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">'+escapeHtml(b.status)+'</span>';
            const amount = '₦' + Number(b.amount).toLocaleString();

            return `
                <a href="/bookings/${b.id}/chat" class="block p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                <i data-feather="user" class="w-5 h-5 text-gray-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">${title}</h4>
                                <p class="text-sm text-gray-600">With: ${provider}</p>
                                <p class="text-xs text-gray-500">${last}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-3">${unread}${statusBadge}</div>
                            <p class="text-sm font-medium text-gray-900 mt-1">${amount}</p>
                        </div>
                    </div>
                </a>
            `;
        }

        function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }

        function timeAgo(iso){
            try { const d = new Date(iso); const now = new Date(); const sec = Math.floor((now - d)/1000); if (sec < 60) return sec+'s'; if (sec < 3600) return Math.floor(sec/60)+'m'; if (sec < 86400) return Math.floor(sec/3600)+'h'; return Math.floor(sec/86400)+'d'; } catch(e){ return '' }
        }

        let timeout = null;
        function doFetch(){
            const q = searchInput.value.trim();
            const sort = sortSelect.value;
            const params = new URLSearchParams({ q, sort });
            fetch(location.pathname + '?' + params.toString(), { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(payload => {
                    const list = payload.data || [];
                    listRoot.innerHTML = list.map(buildItemHtml).join('') || '<div class="text-center py-8"><p class="text-gray-600">No conversations found</p></div>';
                })
                .catch(err => console.error(err));
        }

        if (searchInput && sortSelect && listRoot) {
            searchBtn.addEventListener('click', function(e){ e.preventDefault(); doFetch(); });
            searchInput.addEventListener('input', function(){ clearTimeout(timeout); timeout = setTimeout(doFetch, 450); });
            sortSelect.addEventListener('change', doFetch);
        }
    })();
</script>
@endpush