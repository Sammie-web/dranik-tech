<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pending Vendors</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($vendors->count())
                        <div class="space-y-3">
                            @foreach($vendors as $vendor)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $vendor->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $vendor->email }} · {{ $vendor->phone }}</p>
                                    </div>
                                    <div class="space-x-2">
                                        <a href="{{ route('admin.vendors.review', $vendor) }}" class="px-3 py-1 text-sm border border-gray-300 rounded">View Documents</a>
                                        @if(!$vendor->is_verified)
                                            <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST" class="inline">
                                                @csrf
                                                <button class="px-3 py-1 bg-black text-white rounded text-sm"
                                                    @if(request()->routeIs('admin.vendors.review') && request()->route('admin.vendors.review') == route('admin.vendors.review', $vendor)) disabled @endif
                                                >Approve</button>
                                            </form>
                                        @endif
                                        @if($vendor->is_verified)
                                            <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" class="inline">
                                                @csrf
                                                <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">Reject</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $vendors->links() }}</div>
                    @else
                        <p class="text-gray-600">No pending vendors</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


