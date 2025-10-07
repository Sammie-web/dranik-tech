<x-app-layout>
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Review Vendor Documents</h2>
        <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">Vendor: {{ $vendor->name }}</h3>
            <p class="text-gray-600 mb-2">Email: {{ $vendor->email }}</p>
            <p class="text-gray-600 mb-2">Phone: {{ $vendor->phone }}</p>
        </div>
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Uploaded Documents</h4>
            @if($vendor->documents && count($vendor->documents))
                <ul class="space-y-2">
                    @foreach($vendor->documents as $doc)
                        <li>
                            <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="text-blue-600 underline">{{ $doc->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No documents uploaded.</p>
            @endif
        </div>
        <div class="flex gap-4 mt-8">
            <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Approve Vendor</button>
            </form>
            <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Reject Vendor</button>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
