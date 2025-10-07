<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full border-gray-300 rounded" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full border-gray-300 rounded" required />
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
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" class="mt-1 w-full border-gray-300 rounded">
                                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="provider" {{ old('role', $user->role) === 'provider' ? 'selected' : '' }}>Provider</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_verified" value="1" class="rounded" {{ old('is_verified', $user->is_verified) ? 'checked' : '' }} />
                                <span class="ml-2">Verified</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $user->is_active) ? 'checked' : '' }} />
                                <span class="ml-2">Active</span>
                            </label>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-gray-300 rounded mr-2">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-black text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
