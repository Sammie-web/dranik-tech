<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Services</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($services->count())
                        <div class="space-y-3">
                            @foreach($services as $service)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $service->title }}</p>
                                        <p class="text-sm text-gray-600">Provider: {{ $service->provider->name }} · Category: {{ optional($service->category)->name }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $services->links() }}</div>
                    @else
                        <p class="text-gray-600">No services found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


