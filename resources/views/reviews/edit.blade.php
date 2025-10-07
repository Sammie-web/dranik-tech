<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Review') }}
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
                                    <span>Original Review: {{ $review->created_at->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>₦{{ number_format($review->booking->amount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Notice -->
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <i data-feather="info" class="w-5 h-5 text-yellow-600 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-800">
                                    <strong>Note:</strong> Reviews can only be edited within 7 days of submission. 
                                    You have {{ 7 - $review->created_at->diffInDays(now()) }} day(s) remaining to edit this review.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form -->
                    <form method="POST" action="{{ route('reviews.update', $review) }}" enctype="multipart/form-data" 
                          x-data="reviewForm({{ $review->rating }})" @submit.prevent="submitForm">
                        @csrf
                        @method('PATCH')

                        <!-- Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                How would you rate this service? *
                            </label>
                            <div class="flex items-center space-x-2">
                                <template x-for="star in 5" :key="star">
                                    <button type="button" 
                                            @click="setRating(star)"
                                            @mouseenter="hoverRating = star"
                                            @mouseleave="hoverRating = 0"
                                            class="text-3xl transition-colors duration-200 focus:outline-none"
                                            :class="{
                                                'text-yellow-400': star <= (hoverRating || rating),
                                                'text-gray-300': star > (hoverRating || rating)
                                            }">
                                        ★
                                    </button>
                                </template>
                                <span x-show="rating > 0" class="ml-3 text-sm text-gray-600">
                                    <span x-text="getRatingText()"></span>
                                </span>
                            </div>
                            <input type="hidden" name="rating" :value="rating" required>
                            @error('rating')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Share your experience (optional)
                            </label>
                            <textarea id="comment" name="comment" rows="4" 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black"
                                      placeholder="Tell others about your experience with this service..."
                                      maxlength="1000" x-ref="comment">{{ old('comment', $review->comment) }}</textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                <span x-text="$refs.comment ? $refs.comment.value.length : 0">{{ strlen($review->comment ?? '') }}</span>/1000 characters
                            </div>
                            @error('comment')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Images -->
                        @if($review->images && count($review->images) > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Photos
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($review->images as $image)
                                        <div class="relative">
                                            <img src="{{ $image }}" alt="Review photo" 
                                                 class="w-full h-24 object-cover rounded-lg">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Note: Adding new photos will be added to your existing photos.
                                </p>
                            </div>
                        @endif

                        <!-- Add New Images -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Add new photos (optional)
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <i data-feather="upload" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-black hover:text-gray-800 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-black">
                                            <span>Upload photos</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" 
                                                   @change="handleImageUpload">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB each
                                    </p>
                                </div>
                            </div>
                            
                            <!-- New Image Preview -->
                            <div x-show="selectedImages.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <template x-for="(image, index) in selectedImages" :key="index">
                                    <div class="relative">
                                        <img :src="image.url" :alt="'Preview ' + (index + 1)" 
                                             class="w-full h-24 object-cover rounded-lg">
                                        <button type="button" @click="removeImage(index)"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors duration-200">
                                            ×
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            @error('images.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('reviews.show', $review) }}" 
                               class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                                Cancel
                            </a>
                            
                            <button type="submit" 
                                    :disabled="rating === 0 || isSubmitting"
                                    :class="{
                                        'bg-gray-400 cursor-not-allowed': rating === 0 || isSubmitting,
                                        'bg-black hover:bg-gray-800': rating > 0 && !isSubmitting
                                    }"
                                    class="px-6 py-3 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center">
                                <span x-show="!isSubmitting">Update Review</span>
                                <span x-show="isSubmitting" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reviewForm', (initialRating) => ({
                rating: initialRating,
                hoverRating: 0,
                selectedImages: [],
                isSubmitting: false,
                
                setRating(star) {
                    this.rating = star;
                },
                
                getRatingText() {
                    const texts = {
                        1: 'Poor',
                        2: 'Fair',
                        3: 'Good',
                        4: 'Very Good',
                        5: 'Excellent'
                    };
                    return texts[this.rating] || '';
                },
                
                handleImageUpload(event) {
                    const files = Array.from(event.target.files);
                    
                    files.forEach(file => {
                        if (file.type.startsWith('image/') && file.size <= 2048000) { // 2MB limit
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.selectedImages.push({
                                    file: file,
                                    url: e.target.result
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                },
                
                removeImage(index) {
                    this.selectedImages.splice(index, 1);
                    // Reset file input
                    document.getElementById('images').value = '';
                },
                
                submitForm(event) {
                    if (this.rating === 0) {
                        alert('Please select a rating before updating your review.');
                        return;
                    }
                    
                    this.isSubmitting = true;
                    event.target.submit();
                }
            }))
        });
        
        // Character counter for comment
        document.addEventListener('DOMContentLoaded', function() {
            const commentTextarea = document.getElementById('comment');
            if (commentTextarea) {
                commentTextarea.addEventListener('input', function() {
                    // Update character count in Alpine.js context
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

