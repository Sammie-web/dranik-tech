<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif
                    <div id="users-table">
                        <div class="hidden md:block">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($user->role) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right space-x-2">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 text-xs border border-gray-300 rounded">Edit</a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="px-3 py-1 text-xs border border-red-300 text-red-700 rounded">Delete</button>
                                                </form>
                                                <form action="{{ route('admin.login-as', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="px-3 py-1 text-xs bg-black text-white rounded">Login as</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Cards -->
                        <div class="md:hidden space-y-4">
                            @foreach($users as $user)
                                <div class="border rounded-lg p-4 bg-white shadow-sm flex flex-col gap-2">
                                    <div class="flex items-center justify-between">
                                        <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">{{ ucfirst($user->role) }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600 break-all">{{ $user->email }}</div>
                                    <div class="flex gap-2 mt-2 flex-wrap">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 text-xs border border-gray-300 rounded">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 text-xs border border-red-300 text-red-700 rounded">Delete</button>
                                        </form>
                                        <form action="{{ route('admin.login-as', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="px-3 py-1 text-xs bg-black text-white rounded">Login as</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $users->onEachSide(1)->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('click', function(e) {
            const anchor = e.target.closest('#users-table .pagination a');
            if (anchor) {
                e.preventDefault();
                const url = anchor.getAttribute('href');
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.querySelector('#users-table');
                        if (newTable) {
                            document.querySelector('#users-table').innerHTML = newTable.innerHTML;
                            window.history.replaceState({}, '', url);
                        } else {
                            window.location.href = url;
                        }
                    });
            }
        });
    </script>
</x-app-layout>


