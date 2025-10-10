<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Availability</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('provider.manage.availability.update') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            @foreach($days as $day)
                                @php
                                    $a = $availabilities->has($day) ? $availabilities->get($day) : null;
                                @endphp
                                <div class="flex items-center gap-4 border p-4 rounded">
                                    <div class="w-40 font-medium">{{ ucfirst($day) }}</div>
                                    <div class="flex items-center gap-3">
                                        <label class="flex items-center gap-2">
                                            {{-- ensure a value is always posted for is_available (0 when unchecked) --}}
                                            <input type="hidden" name="days[{{ $day }}][is_available]" value="0">
                                            <input type="checkbox" class="availability-toggle" name="days[{{ $day }}][is_available]" value="1" {{ $a && $a->is_available ? 'checked' : '' }}>
                                            <span class="text-sm">Available</span>
                                        </label>

                                        <input type="time" name="days[{{ $day }}][start_time]" value="{{ $a && $a->start_time ? \Carbon\Carbon::parse($a->start_time)->format('H:i') : '' }}" class="px-2 py-1 border rounded start-time">
                                        <span>to</span>
                                        <input type="time" name="days[{{ $day }}][end_time]" value="{{ $a && $a->end_time ? \Carbon\Carbon::parse($a->end_time)->format('H:i') : '' }}" class="px-2 py-1 border rounded end-time">
                                    </div>
                                    <input type="hidden" name="days[{{ $day }}][day]" value="{{ $day }}" />
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-black text-white rounded">Save Availability</button>
                        </div>
                    </form>

                    @push('scripts')
                    <script>
                        (function(){
                            document.querySelectorAll('.availability-toggle').forEach(function(cb){
                                const container = cb.closest('.flex');
                                const start = container.querySelector('.start-time');
                                const end = container.querySelector('.end-time');
                                function update() {
                                    const enabled = cb.checked;
                                    if (start) start.disabled = !enabled;
                                    if (end) end.disabled = !enabled;
                                }
                                cb.addEventListener('change', update);
                                update();
                            });
                        })();
                    </script>
                    @endpush

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
