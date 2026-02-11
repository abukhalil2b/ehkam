<x-app-layout>
    <x-slot name="header">
        تعديل مستهدفات المؤشر: {{ $indicator->title }}
    </x-slot>

    <form method="POST" action="{{ route('indicator_target.update', $indicator) }}">
        @csrf
        @method('PUT')

        <div class="max-w-4xl mx-auto py-6 space-y-4">

            @foreach($targets as $index => $target)
                <div class="flex items-center gap-4 bg-white p-4 rounded-lg shadow border">
                    
                    <div class="w-24 text-sm font-semibold text-gray-600">
                        {{ $target->year }}
                    </div>

                    <input type="hidden" name="targets[{{ $index }}][id]" value="{{ $target->id }}">

                    <input
                        type="number"
                        step="0.01"
                        name="targets[{{ $index }}][target_value]"
                        value="{{ old("targets.$index.target_value", $target->target_value) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-indigo-200"
                        required
                    >

                    <span class="text-sm text-gray-500">%</span>
                </div>
            @endforeach

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    حفظ التعديلات
                </button>
            </div>

        </div>
    </form>
</x-app-layout>
