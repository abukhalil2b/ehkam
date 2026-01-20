<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ __('تعريفات سير العمل') }}
            </h2>
            <a href="{{ route('admin.workflow.definitions.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ __('إنشاء سير عمل جديد') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded overflow-x-auto">
            @if($workflows->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">{{ __('لا توجد تعريفات سير عمل حالياً') }}</p>
                    <a href="{{ route('admin.workflow.definitions.create') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">
                        {{ __('إنشاء أول سير عمل') }}
                    </a>
                </div>
            @else
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">{{ __('اسم سير العمل') }}</th>
                            <th class="px-6 py-3">{{ __('نوع الكيان') }}</th>
                            <th class="px-6 py-3">{{ __('الوصف') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('عدد المراحل') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('عدد الأنشطة') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('الحالة') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workflows as $workflow)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">{{ $workflow->id }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.workflow.definitions.show', $workflow) }}" class="text-indigo-600 hover:underline font-medium">
                                        {{ $workflow->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">
                                    {{ Str::afterLast($workflow->entity_type, '\\') }}
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ Str::limit($workflow->description, 50) ?: '-' }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $workflow->stages_count }}</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ $workflow->instances_count }}</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($workflow->is_active)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">{{ __('نشط') }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ __('غير نشط') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center flex justify-center gap-2">
                                    <a href="{{ route('admin.workflow.definitions.show', $workflow) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                        {{ __('عرض') }}
                                    </a>
                                    <a href="{{ route('admin.workflow.definitions.edit', $workflow) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                                        {{ __('تعديل') }}
                                    </a>
                                    <a href="{{ route('admin.workflow.definitions.assign', $workflow) }}" class="bg-purple-500 text-white px-3 py-1 rounded text-xs">
                                        {{ __('ربط بالأنشطة') }}
                                    </a>
                                    @if($workflow->activities_count == 0)
                                        <form action="{{ route('admin.workflow.definitions.destroy', $workflow) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                {{ __('حذف') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>