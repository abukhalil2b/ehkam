<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">

        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($indicators as $indicator)
                <div class="border-b last:border-b-0 py-4 flex justify-between items-center  {{ $indicator->is_main ? 'bg-white' : 'bg-gray-100' }} p-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $indicator->title }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            مؤشر: {{ $indicator->is_main ? 'رئيسي' : 'فرعي' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            المستهدف: {{ $indicator->target_for_indicator }}
                        </p>
                        <p class="text-sm text-gray-500">
                            سنة المؤشر: {{ $indicator->current_year }}
                        </p>
                    </div>

                    <!-- Placeholder for future actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('indicator.edit', $indicator->id) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            تعديل
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    لا توجد مؤشرات مسجلة لهذه السنة
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
