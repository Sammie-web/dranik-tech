@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h1 class="text-2xl font-semibold text-gray-800">{{ $category->name }}</h1>
        @if($services->count())
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <div class="bg-white p-4 rounded-lg shadow">
                        <a href="{{ route('services.show', $service) }}" class="text-lg font-medium text-gray-800">{{ $service->title }}</a>
                        <div class="text-sm text-gray-500">by {{ $service->provider?->name ?? 'Unknown' }}</div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $services->links() }}</div>
        @else
            <p class="mt-4 text-gray-600">No services found in this category.</p>
        @endif
    </div>
</div>
@endsection
