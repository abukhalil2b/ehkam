<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('ربط سير العمل بالأنشطة') }}: {{ $workflow->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('admin.workflow.definitions.store-assignment', $workflow) }}" method="POST">
                @csrf

                <div class="mb-4 bg-yellow-50 text-yellow-800 px-4 py-3 rounded">
                    <p>{{ __('اختر الأنشطة التي تريد ربطها بسير العمل هذا. يرجى الملاحظة أن الأنشطة التي بدأت بالفعل في سير عمل آخر لن تظهر هنا.') }}</p>
                </div>

                @if($activities->isEmpty())
                    <div class="text-center py-6 text-gray-500">
                        {{ __('لا توجد أنشطة متاحة للربط.') }}
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 border-b">
                                    <th class="px-4 py-2 w-10">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300">
                                    </th>
                                    <th class="px-4 py-2">{{ __('العنصر') }}</th>
                                    <th class="px-4 py-2">{{ __('المعرف') }}</th>
                                    <th class="px-4 py-2">{{ __('الحالة الحالية') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $item)
                                    @php
                                        // Try to get current workflow instance info
                                        $instance = $item->workflowInstance;
                                        $isLinked = $instance && $instance->workflow_id == $workflow->id;
                                    @endphp
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" name="activity_ids[]" value="{{ $item->id }}" 
                                                class="rounded border-gray-300 activity-checkbox"
                                                {{ $isLinked ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-4 py-2 font-medium">
                                            {{ $item->title ?? $item->name ?? __('بدون عنوان') }}
                                            @if(isset($item->project))
                                                <div class="text-xs text-gray-500">{{ $item->project->name ?? '' }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-gray-600">
                                            {{ $item->id }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-xs
                                                {{ $isLinked ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $isLinked ? __('مرتبط حالياً') : __('غير مرتبط') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="flex gap-2 mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        {{ __('حفظ التغييرات') }}
                    </button>
                    <a href="{{ route('admin.workflow.definitions.index') }}"
                        class="px-4 py-2 border rounded hover:bg-gray-50">
                        {{ __('إلغاء') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.activity-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</x-app-layout>
