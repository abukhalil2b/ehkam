<x-app-layout>
    <x-slot name="header">
        تعديل المؤشر: {{ $indicator->title }}
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-xl" dir="rtl">
        {{-- Use PUT method for update --}}
        <form method="POST" action="{{ route('indicator.update', $indicator) }}">
            @csrf
            @method('PUT')
            
            @include('indicator._form', [
                'indicator' => $indicator,
                'sectors' => $sectors,
                'selectedSectorIds' => $selectedSectorIds,
            ])

            <div class="flex justify-start mt-6">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    تحديث المؤشر
                </button>
            </div>
        </form>
    </div>
</x-app-layout>