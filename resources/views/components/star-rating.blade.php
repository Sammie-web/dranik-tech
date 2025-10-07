@props(['rating' => 0, 'maxRating' => 5, 'size' => 'md', 'showNumber' => true, 'interactive' => false])

@php
    $sizeClasses = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl'
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="flex items-center space-x-1" 
     @if($interactive) x-data="{ rating: {{ $rating }}, hoverRating: 0 }" @endif>
    @for($i = 1; $i <= $maxRating; $i++)
        @if($interactive)
            <button type="button" 
                    @click="rating = {{ $i }}"
                    @mouseenter="hoverRating = {{ $i }}"
                    @mouseleave="hoverRating = 0"
                    class="{{ $sizeClass }} transition-colors duration-200 focus:outline-none"
                    :class="{
                        'text-yellow-400': {{ $i }} <= (hoverRating || rating),
                        'text-gray-300': {{ $i }} > (hoverRating || rating)
                    }">
                ★
            </button>
        @else
            <span class="{{ $sizeClass }} {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
        @endif
    @endfor
    
    @if($showNumber && $rating > 0)
        <span class="ml-1 text-sm text-gray-600 font-medium">
            {{ number_format($rating, 1) }}
        </span>
    @endif
</div>

