<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Platform Settings</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('status'))
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">{{ session('status') }}</div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Platform Settings</h3>

                    <form action="{{ route('admin.platform-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Site Logo</label>
                                @if(!empty($settings['site_logo']))
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-16 mb-2">
                                @endif
                                <input type="file" name="logo" accept="image/*" class="mt-1 block w-full">
                                @error('logo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Favicon (16x16/32x32)</label>
                                @if(!empty($settings['site_favicon']))
                                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 mb-2">
                                @endif
                                <input type="file" name="favicon" accept="image/*" class="mt-1 block w-full">
                                @error('favicon')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Site Name</label>
                                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('site_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Support Email</label>
                                <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email'] ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('support_email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Maintenance mode toggle removed per request --}}

                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


