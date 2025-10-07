<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Biodata</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('provider.biodata.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full border-gray-300 rounded" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full border-gray-300 rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" class="mt-1 w-full border-gray-300 rounded">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bio</label>
                            <textarea name="bio" class="mt-1 w-full border-gray-300 rounded">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-black text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


