<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('تعديل سير العمل') }}: {{ $definition->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Workflow Info --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-yellow-500 text-white px-4 py-3">
                        <h3 class="font-semibold">{{ __('معلومات سير العمل') }}</h3>
                    </div>
                    <div class="p-4">
                        <form action="{{ route('admin.workflow.definitions.update', $definition) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('اسم سير العمل') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $definition->name) }}" required
                                       class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="entity_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('نوع الكيان') }}</label>
                                <select id="entity_type" name="entity_type" class="w-full border rounded px-3 py-2 bg-gray-50" readonly disabled>
                                    <option value="{{ $definition->entity_type }}">{{ Str::afterLast($definition->entity_type, '\\') }}</option>
                                </select>
                                <input type="hidden" name="entity_type" value="{{ $definition->entity_type }}">
                                <p class="text-gray-500 text-sm mt-1">{{ __('لا يمكن تغيير نوع الكيان بعد الإنشاء') }}</p>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('الوصف') }}</label>
                                <textarea id="description" name="description" rows="3"
                                          class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $definition->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $definition->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">{{ __('نشط') }}</span>
                                </label>
                            </div>

                            <button type="submit" class="w-full bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                {{ __('حفظ التغييرات') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Stages Management --}}
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-blue-500 text-white px-4 py-3 flex justify-between items-center">
                        <h3 class="font-semibold">{{ __('مراحل سير العمل') }}</h3>
                        <button type="button" onclick="document.getElementById('addStageModal').classList.remove('hidden')"
                                class="bg-white text-blue-600 px-3 py-1 rounded text-sm">
                            {{ __('إضافة مرحلة') }}
                        </button>
                    </div>
                    <div class="p-4">
                        @if($definition->stages->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-gray-500 mt-2">{{ __('لم يتم إضافة مراحل بعد') }}</p>
                            </div>
                        @else
                            {{-- Visual Flow --}}
                            <div class="flex flex-wrap items-center justify-center gap-2 mb-6 p-4 bg-gray-50 rounded">
                                @foreach($definition->stages as $index => $stage)
                                    <div class="bg-white border p-3 rounded text-center min-w-[120px]">
                                        <div class="font-medium">{{ $stage->name }}</div>
                                        <div class="text-gray-500 text-sm">{{ $stage->team->name }}</div>
                                        <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full text-xs mt-1 inline-block">{{ $stage->order }}</span>
                                    </div>
                                    @if(!$loop->last)
                                        <svg class="w-6 h-6 text-indigo-500 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Stages Table --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-right">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2">{{ __('الترتيب') }}</th>
                                            <th class="px-4 py-2">{{ __('اسم المرحلة') }}</th>
                                            <th class="px-4 py-2">{{ __('الفريق المسؤول') }}</th>
                                            <th class="px-4 py-2 text-center">{{ __('المدة (أيام)') }}</th>
                                            <th class="px-4 py-2 text-center">{{ __('موافقة') }}</th>
                                            <th class="px-4 py-2 text-center">{{ __('إرجاع') }}</th>
                                            <th class="px-4 py-2 text-center">{{ __('الإجراءات') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($definition->stages as $stage)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">{{ $stage->order }}</span>
                                                </td>
                                                <td class="px-4 py-2">{{ $stage->name }}</td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('admin.workflow.teams.show', $stage->team) }}" class="text-indigo-600 hover:underline">
                                                        {{ $stage->team->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $stage->allowed_days ?? '-' }}</span>
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($stage->can_approve)
                                                        <span class="text-green-600">✓</span>
                                                    @else
                                                        <span class="text-red-600">✗</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($stage->can_return)
                                                        <span class="text-green-600">✓</span>
                                                    @else
                                                        <span class="text-red-600">✗</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <div class="flex justify-center gap-1">
                                                        <button type="button" onclick="openEditStageModal({{ json_encode($stage) }})"
                                                                class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                                            {{ __('تعديل') }}
                                                        </button>
                                                        @if($stage->canBeDeleted())
                                                            <form action="{{ route('admin.workflow.stages.destroy', $stage) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد؟') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                                                                    {{ __('حذف') }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Reindex Button --}}
                            <div class="mt-4 text-left">
                                <form action="{{ route('admin.workflow.stages.reindex', $definition) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                        {{ __('إعادة ترتيب الأرقام') }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Stage Modal --}}
    <div id="addStageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-md rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('إضافة مرحلة جديدة') }}</h3>

            <form action="{{ route('admin.workflow.stages.store', $definition) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="stage_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('اسم المرحلة') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="stage_name" name="name" required
                           placeholder="{{ __('مثال: مرحلة المراجعة') }}"
                           class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label for="stage_team" class="block text-sm font-medium text-gray-700 mb-1">{{ __('الفريق المسؤول') }} <span class="text-red-500">*</span></label>
                    <select id="stage_team" name="team_id" required class="w-full border rounded px-3 py-2">
                        <option value="">{{ __('اختر الفريق') }}</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="stage_allowed_days" class="block text-sm font-medium text-gray-700 mb-1">{{ __('المدة المسموحة (أيام)') }} <span class="text-red-500">*</span></label>
                    <input type="number" id="stage_allowed_days" name="allowed_days" required min="1"
                           placeholder="{{ __('مثال: 5') }}"
                           class="w-full border rounded px-3 py-2">
                    <p class="text-gray-500 text-sm mt-1">{{ __('سيتم تصعيد المستوى عند تجاوز المدة') }}</p>
                </div>

                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="can_approve" value="1" checked class="rounded border-gray-300">
                        <span class="text-sm">{{ __('يمكن الموافقة') }}</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="can_return" value="1" checked class="rounded border-gray-300">
                        <span class="text-sm">{{ __('يمكن الإرجاع') }}</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('addStageModal').classList.add('hidden')"
                            class="px-4 py-2 border rounded">
                        {{ __('إلغاء') }}
                    </button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
                        {{ __('إضافة المرحلة') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Stage Modal --}}
    <div id="editStageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-md rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('تعديل المرحلة') }}</h3>

            <form id="editStageForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="edit_stage_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('اسم المرحلة') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_stage_name" name="name" required class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label for="edit_stage_team" class="block text-sm font-medium text-gray-700 mb-1">{{ __('الفريق المسؤول') }} <span class="text-red-500">*</span></label>
                    <select id="edit_stage_team" name="team_id" required class="w-full border rounded px-3 py-2">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="edit_stage_allowed_days" class="block text-sm font-medium text-gray-700 mb-1">{{ __('المدة المسموحة (أيام)') }} <span class="text-red-500">*</span></label>
                    <input type="number" id="edit_stage_allowed_days" name="allowed_days" required min="1"
                           class="w-full border rounded px-3 py-2">
                    <p class="text-gray-500 text-sm mt-1">{{ __('سيتم تصعيد المستوى عند تجاوز المدة') }}</p>
                </div>

                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="edit_can_approve" name="can_approve" value="1" class="rounded border-gray-300">
                        <span class="text-sm">{{ __('يمكن الموافقة') }}</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="edit_can_return" name="can_return" value="1" class="rounded border-gray-300">
                        <span class="text-sm">{{ __('يمكن الإرجاع') }}</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('editStageModal').classList.add('hidden')"
                            class="px-4 py-2 border rounded">
                        {{ __('إلغاء') }}
                    </button>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">
                        {{ __('حفظ التغييرات') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditStageModal(stage) {
            document.getElementById('editStageForm').action = '/admin/workflow/stages/' + stage.id;
            document.getElementById('edit_stage_name').value = stage.name;
            document.getElementById('edit_stage_team').value = stage.team_id;
            document.getElementById('edit_stage_allowed_days').value = stage.allowed_days || '';
            document.getElementById('edit_can_approve').checked = stage.can_approve;
            document.getElementById('edit_can_return').checked = stage.can_return;
            document.getElementById('editStageModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>