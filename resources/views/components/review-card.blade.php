@props(['review', 'showService' => false, 'showProvider' => false])

<div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
    <!-- Review Header -->
    <div class="flex items-start justify-between mb-4">
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

    <!-- Service/Provider Info (if requested) -->
    @if($showService && $review->service)
        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                @if($review->service->images && count($review->service->images) > 0)
                    <img src="{{ $review->service->images[0] }}" alt="{{ $review->service->title }}" 
                         class="w-12 h-12 object-cover rounded-lg">
                @else
                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i data-feather="image" class="w-4 h-4 text-gray-400"></i>
                    </div>
                @endif
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ $review->service->title }}</h4>
                    <p class="text-xs text-gray-500">{{ $review->service->category->name }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($showProvider && $review->provider)
        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                @if($review->provider->avatar)
                    <img src="{{ $review->provider->avatar }}" alt="{{ $review->provider->name }}" 
                         class="w-12 h-12 object-cover rounded-full">
                @else
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                            {{ substr($review->provider->name, 0, 1) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ $review->provider->name }}</h4>
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-xs {{ $i <= $review->provider->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                        <span class="text-xs text-gray-500 ml-1">({{ $review->provider->total_reviews }})</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Review Content -->
    @if($review->comment)
        <p class="text-gray-700 mb-4 line-clamp-3">{{ $review->comment }}</p>
    @endif

    <!-- Review Images -->
    @if($review->images && count($review->images) > 0)
        <div class="grid grid-cols-3 gap-2 mb-4">
            @foreach(array_slice($review->images, 0, 3) as $index => $image)
                <div class="aspect-square relative">
                    <img src="{{ $image }}" alt="Review photo" 
                         class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity duration-200"
                         onclick="openImageModal('{{ $image }}')">
                    @if($index === 2 && count($review->images) > 3)
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xs font-medium">+{{ count($review->images) - 3 }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Review Footer -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4 text-sm">
            @if($review->is_verified)
                <span class="flex items-center text-green-600">
                    <i data-feather="check-circle" class="w-4 h-4 mr-1"></i>
                    Verified
                </span>
            @endif
            
            <span class="text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
        </div>
        
        <a href="{{ route('reviews.show', $review) }}" 
           class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
            View Details
        </a>
    </div>
</div>

@once
@push('scripts')
<script>
    function openImageModal(imageSrc) {
        // Create modal if it doesn't exist
        let modal = document.getElementById('imageModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full">
                    <img id="modalImage" src="" alt="Review photo" class="max-w-full max-h-full object-contain">
                    <button onclick="closeImageModal()" 
                            class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors duration-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Add event listeners
            modal.addEventListener('click', function(event) {
                if (event.target === this) {
                    closeImageModal();
                }
            });
            
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeImageModal();
                }
            });
        }
        
        document.getElementById('modalImage').src = imageSrc;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
</script>
@endpush
@endonce

