<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Active Services</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Active Services</h3>
                    @if($services->count())
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($services as $service)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $service->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">₦{{ number_format($service->price) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $services->links() }}</div>
                    @else
                        <p class="text-gray-600">No active services</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


