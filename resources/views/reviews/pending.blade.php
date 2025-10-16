<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Leave a review</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Completed services awaiting your review</h3>

                @if($bookings->isEmpty())
                    <div class="text-center py-8">
                        <i data-feather="star" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-600">You have no completed bookings pending review.</p>
                        <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Browse services</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($bookings as $booking)
                            <div id="booking-{{ $booking->id }}" class="p-4 border border-gray-200 rounded-lg flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $booking->service->title }}</h4>
                                    <p class="text-sm text-gray-600">With: {{ $booking->provider->name }}</p>
                                    <p class="text-xs text-gray-500">Completed: {{ $booking->completed_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <button data-booking-id="{{ $booking->id }}" class="write-review-btn bg-black text-white px-4 py-2 rounded">Write Review</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $bookings->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    (function(){
        function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
        function qsa(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

        // Insert modal into DOM
        const modalHtml = `
        <div id="review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg w-full max-w-2xl p-6">
                <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                <form id="review-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="_method" value="POST" />
                    <input type="hidden" name="booking_id" id="modal-booking-id" value="" />

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Rating</label>
                        <select name="rating" id="modal-rating" class="border px-3 py-2 rounded w-24">
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Comment</label>
                        <textarea name="comment" id="modal-comment" rows="4" class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Images (optional)</label>
                        <input type="file" name="images[]" id="modal-images" multiple accept="image/*" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div id="modal-error" class="text-sm text-red-600"></div>
                        <div class="space-x-2">
                            <button type="button" id="modal-cancel" class="px-4 py-2 rounded border">Cancel</button>
                            <button type="submit" id="modal-submit" class="px-4 py-2 rounded bg-black text-white">Submit Review</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const modal = qs('#review-modal');
        const form = qs('#review-form');
        const cancelBtn = qs('#modal-cancel');
        const submitBtn = qs('#modal-submit');
        const bookingIdInput = qs('#modal-booking-id');
        const errorBox = qs('#modal-error');

        function openModal(bookingId) {
            bookingIdInput.value = bookingId;
            errorBox.textContent = '';
            modal.classList.remove('hidden');
        }
        function closeModal(){ modal.classList.add('hidden'); }

        qsa('.write-review-btn').forEach(btn => {
            btn.addEventListener('click', function(){
                const id = this.getAttribute('data-booking-id');
                openModal(id);
            });
        });

        cancelBtn.addEventListener('click', function(){ closeModal(); });

        form.addEventListener('submit', function(e){
            e.preventDefault();
            errorBox.textContent = '';
            const bookingId = bookingIdInput.value;
            if (!bookingId) return errorBox.textContent = 'Missing booking id.';

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('rating', qs('#modal-rating').value);
            fd.append('comment', qs('#modal-comment').value);
            const files = qs('#modal-images').files;
            for (let i=0;i<files.length;i++) fd.append('images[]', files[i]);

            submitBtn.disabled = true;

            fetch(`/bookings/${bookingId}/review`, {
                method: 'POST',
                body: fd,
                headers: {
                    'Accept': 'application/json'
                }
            }).then(async res => {
                submitBtn.disabled = false;
                if (res.ok) {
                    const data = await res.json();
                    // remove the booking from the list
                    const el = document.getElementById('booking-' + bookingId);
                    if (el) el.remove();
                    closeModal();
                } else if (res.status === 422) {
                    const json = await res.json();
                    const messages = json.errors ? Object.values(json.errors).flat().join(' ') : 'Validation failed.';
                    errorBox.textContent = messages;
                } else {
                    const txt = await res.text();
                    errorBox.textContent = 'Failed to submit review. Please try again.';
                    console.error('Review submit error', res.status, txt);
                }
            }).catch(err => {
                submitBtn.disabled = false;
                errorBox.textContent = 'Network error. Please try again.';
                console.error(err);
            });
        });
    })();
</script>
@endpush