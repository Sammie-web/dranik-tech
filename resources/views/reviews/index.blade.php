<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reviews for') }} {{ $service->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Service Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        @if($service->images && count($service->images) > 0)
                            <img src="{{ $service->images[0] }}" alt="{{ $service->title }}" 
                                 class="w-32 h-32 object-cover rounded-lg">
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i data-feather="image" class="w-12 h-12 text-gray-400"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $service->title }}</h1>
                            <p class="text-gray-600 mb-4">{{ $service->category->name }}</p>
                            
                            <div class="flex items-center space-x-6 mb-4">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl {{ $i <= $service->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                    <span class="ml-2 text-lg font-semibold">{{ number_format($service->rating, 1) }}</span>
                                    <span class="ml-1 text-gray-500">({{ $service->total_reviews }} reviews)</span>
                                </div>
                                <div class="text-gray-500">•</div>
                                <div class="text-lg font-semibold text-gray-900">₦{{ number_format($service->price) }}</div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('services.show', $service) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    View Service Details
                                </a>
                                <a href="{{ route('reviews.provider', $service->provider) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    All Provider Reviews
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Rating Breakdown -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating Breakdown</h3>
                            
                            <!-- Overall Rating -->
                            <div class="text-center mb-6">
                                <div class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($service->rating, 1) }}</div>
                                <div class="flex justify-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl {{ $i <= $service->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                                <div class="text-sm text-gray-500">{{ $service->total_reviews }} total reviews</div>
                            </div>

                            <!-- Rating Distribution -->
                            <div class="space-y-2">
                                @foreach($ratingBreakdown as $rating => $data)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-medium text-gray-700 w-6">{{ $rating }}★</span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full" 
                                                 style="width: {{ $data['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500 w-8">{{ $data['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="lg:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Customer Reviews ({{ $reviews->total() }})
                                </h3>
                                
                                <!-- Sort Options -->
                                <div class="flex items-center space-x-4">
                                    <select class="border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black text-sm"
                                            onchange="window.location.href = this.value">
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" 
                                                {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>
                                            Newest First
                                        </option>
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" 
                                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                            Oldest First
                                        </option>
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'highest']) }}" 
                                                {{ request('sort') === 'highest' ? 'selected' : '' }}>
                                            Highest Rating
                                        </option>
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'lowest']) }}" 
                                                {{ request('sort') === 'lowest' ? 'selected' : '' }}>
                                            Lowest Rating
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @if($reviews->count() > 0)
                                <div class="space-y-6">
                                    @foreach($reviews as $review)
                                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                            <!-- Review Header -->
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center space-x-3">
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
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $review->customer->name }}</p>
                                                        <div class="flex items-center space-x-2">
                                                            <div class="flex">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                                                @endfor
                                                            </div>
                                                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($review->is_featured)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i data-feather="star" class="w-3 h-3 mr-1"></i>
                                                        Featured
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Review Content -->
                                            @if($review->comment)
                                                <p class="text-gray-700 mb-3">{{ $review->comment }}</p>
                                            @endif

                                            <!-- Review Images -->
                                            @if($review->images && count($review->images) > 0)
                                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3">
                                                    @foreach(array_slice($review->images, 0, 4) as $index => $image)
                                                        <div class="aspect-square relative">
                                                            <img src="{{ $image }}" alt="Review photo" 
                                                                 class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity duration-200"
                                                                 onclick="openImageModal('{{ $image }}')">
                                                            @if($index === 3 && count($review->images) > 4)
                                                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                                                    <span class="text-white text-sm font-medium">+{{ count($review->images) - 4 }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Review Actions -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4 text-sm">
                                                    @if($review->is_verified)
                                                        <span class="flex items-center text-green-600">
                                                            <i data-feather="check-circle" class="w-4 h-4 mr-1"></i>
                                                            Verified
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <a href="{{ route('reviews.show', $review) }}" 
                                                   class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                    View Full Review
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                <div class="mt-6">
                                    {{ $reviews->links() }}
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i data-feather="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
                                    <p class="text-gray-500">Be the first to review this service!</p>
                                </div>
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

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal on background click
        document.getElementById('imageModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageModal();
            }
        });
    </script>
    @endpush
</x-app-layout>

