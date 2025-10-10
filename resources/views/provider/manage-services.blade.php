<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Services</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Your Services</h3>
                        <div>
                            @if(!empty($hasAvailability))
                                <span class="text-sm px-3 py-1 bg-green-100 text-green-800 rounded">Availability configured</span>
                            @else
                                <a href="{{ route('provider.manage.availability') }}" class="text-sm px-3 py-1 bg-yellow-100 text-yellow-800 rounded">Set Availability</a>
                            @endif
                        </div>
                    </div>
                    @if($services->count())
                            @if(empty($hasAvailability))
                                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                                    <p class="text-sm text-yellow-800">You haven't configured your availability yet. It's recommended to <a href="{{ route('provider.manage.availability') }}" class="underline">set your availability</a> so customers can schedule properly.</p>
                                </div>
                            @endif
                        <div class="space-y-3">
                            @foreach($services as $service)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $service->title }}</p>
                                        <p class="text-sm text-gray-600">Category: {{ optional($service->category)->name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('admin.services.assign-category', $service) }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="category_id" class="border-gray-300 rounded">
                                                <option value="">— No Category —</option>
                                                @foreach(\App\Models\ServiceCategory::orderBy('name')->get() as $cat)
                                                    <option value="{{ $cat->id }}" {{ $service->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="px-3 py-1 text-xs bg-black text-white rounded">Assign</button>
                                        </form>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $services->links() }}</div>
                    @else
                        <p class="text-gray-600">No services yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


