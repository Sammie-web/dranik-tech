@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h1 class="text-2xl font-semibold text-gray-800">Categories</h1>
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="block p-4 bg-white rounded-lg shadow hover:shadow-md border border-gray-100">
                    <div class="text-lg font-medium text-gray-800">{{ $category->name }}</div>
                    <div class="text-sm text-gray-500">{{ $category->services_count ?? 0 }} services</div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
