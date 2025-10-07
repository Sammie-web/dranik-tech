<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Create Category</h3>
                            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="text" name="name" class="w-full border-gray-300 rounded" placeholder="Name" required />
                                <textarea name="description" class="w-full border-gray-300 rounded" placeholder="Description (optional)"></textarea>
                                <button class="px-4 py-2 bg-black text-white rounded">Create</button>
                            </form>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Categories</h3>
                            @if(isset($categories) && $categories->count())
                                <div class="space-y-3">
                                    @foreach($categories as $category)
                                        <div class="p-3 border border-gray-200 rounded">
                                            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex items-center justify-between space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name" value="{{ $category->name }}" class="border-gray-300 rounded flex-1" />
                                                <input type="text" name="description" value="{{ $category->description }}" class="border-gray-300 rounded flex-1" />
                                                <button class="px-3 py-1 bg-black text-white rounded text-sm">Save</button>
                                            </form>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="mt-2 text-right" onsubmit="return confirm('Delete category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 border border-red-300 text-red-700 rounded text-xs">Delete</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">{{ $categories->links() }}</div>
                            @else
                                <p class="text-gray-600">No categories</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


