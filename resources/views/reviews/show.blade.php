<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Service Information -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-start space-x-4">
                            @if($review->service->images && count($review->service->images) > 0)
                                <img src="{{ $review->service->images[0] }}" alt="{{ $review->service->title }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i data-feather="image" class="w-8 h-8 text-gray-400"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $review->service->title }}</h3>
                                <p class="text-gray-600 mb-2">{{ $review->service->category->name }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>Provider: {{ $review->provider->name }}</span>
                                    <span>•</span>
                                    <span>Booking: {{ $review->booking->booking_number }}</span>
                                    <span>•</span>
                                    <span>₦{{ number_format($review->booking->amount) }}</span>
                                </div>
                            </div>
                            
                            @if($review->is_featured)
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i data-feather="star" class="w-3 h-3 mr-1"></i>
                                        Featured
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="mb-8">
                        <!-- Rating and Date -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-2xl {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                                <span class="text-lg font-semibold text-gray-900">
                                    {{ $review->rating }}/5
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $review->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            
                            @if($review->customer_id === auth()->id() && $review->created_at->diffInDays(now()) <= 7)
                                <a href="{{ route('reviews.edit', $review) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    Edit Review
                                </a>
                            @endif
                        </div>

                        <!-- Customer Info -->
                        <div class="flex items-center mb-4">
                            @if($review->customer->avatar)
                                <img src="{{ $review->customer->avatar }}" alt="{{ $review->customer->name }}" 
                                     class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ substr($review->customer->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $review->customer->name }}</p>
                                <p class="text-xs text-gray-500">Verified Customer</p>
                            </div>
                        </div>

                        <!-- Comment -->
                        @if($review->comment)
                            <div class="mb-6">
                                <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        @endif

                        <!-- Review Images -->
                        @if($review->images && count($review->images) > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Photos from this review</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($review->images as $image)
                                        <div class="aspect-square">
                                            <img src="{{ $image }}" alt="Review photo" 
                                                 class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity duration-200"
                                                 onclick="openImageModal('{{ $image }}')">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Verification Badge -->
                        @if($review->is_verified)
                            <div class="flex items-center text-sm text-green-600">
                                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                                <span>Verified Review</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('services.show', $review->service) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                View Service
                            </a>
                            <a href="{{ route('reviews.provider', $review->provider) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                View All Provider Reviews
                            </a>
                        </div>

                        <div class="flex items-center space-x-3">
                            <!-- Report Review -->
                            @if($review->customer_id !== auth()->id())
                                <button onclick="openReportModal()" 
                                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                    Report
                                </button>
                            @endif

                            <!-- Admin Actions -->
                            @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('reviews.toggle-featured', $review) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="text-sm {{ $review->is_featured ? 'text-yellow-600 hover:text-yellow-800' : 'text-gray-500 hover:text-gray-700' }} transition-colors duration-200">
                                        {{ $review->is_featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Review -->
                            @if($review->customer_id === auth()->id() || auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('reviews.destroy', $review) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-sm text-red-600 hover:text-red-800 transition-colors duration-200">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <img id="modalImage" src="" alt="Review photo" class="max-w-full max-h-full object-contain">
            <button onclick="closeImageModal()" 
                    class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors duration-200">
                <i data-feather="x" class="w-8 h-8"></i>
            </button>
        </div>
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Review</h3>
            
            <form method="POST" action="{{ route('reviews.report', $review) }}">
                @csrf
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for reporting
                    </label>
                    <textarea id="reason" name="reason" rows="4" required
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black"
                              placeholder="Please explain why you're reporting this review..."></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeReportModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openReportModal() {
            document.getElementById('reportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
                closeReportModal();
            }
        });

        // Close modals on background click
        document.getElementById('imageModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageModal();
            }
        });

        document.getElementById('reportModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeReportModal();
            }
        });
    </script>
    @endpush
</x-app-layout>

