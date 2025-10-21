@props(['user', 'positions', 'units'])

@php
    // Use currentHistory to get the active (end_date is NULL) record.
    $activeHistory = $user->currentHistory;
    $position = $activeHistory?->position;
    $unit = $activeHistory?->organizationalUnit;

    $isAssigned = (bool)$activeHistory;
@endphp

<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6" id="position-card">
    <h2 class="text-xl font-semibold mb-4 text-purple-600 border-b pb-2 dark:text-purple-400">
        المسمى الوظيفي الحالي
    </h2>

    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
        {{-- Display Current Position/Unit --}}
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">المسمى وظيفي:</dt>
            <dd class="text-gray-900 dark:text-white font-semibold">
                {{ $position->title ?? 'غير مخصص' }}
            </dd>
        </div>
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">الوحدة التنظيمية:</dt>
            <dd class="text-gray-900 dark:text-white">
                {{ $unit->name ?? 'غير محدد' }}
            </dd>
        </div>
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">تاريخ البدء:</dt>
            <dd class="text-gray-900 dark:text-white">
                {{ $activeHistory?->start_date ? \Carbon\Carbon::parse($activeHistory->start_date)->format('Y-m-d') : '—' }}
            </dd>
        </div>

        {{-- Status --}}
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">الحالة:</dt>
            <dd class="text-green-600 font-bold">
                {{ $isAssigned ? 'نشط حالياً' : 'لم يتم التعيين بعد' }}
            </dd>
        </div>
    </dl>

    {{-- New Assignment Form (Always creates a new record, closing the old one if it exists) --}}
    @if (Auth::id() === 1)
        <div class="mt-6 pt-4 border-t dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-3 text-blue-500">
                تسجيل تعيين/ترقية جديد
            </h3>
            <form method="POST" action="{{ route('admin_users.update_position', $user) }}" id="new-assignment-form">
                @csrf
                @method('PUT')

                {{-- Hidden input to ensure we don't accidentally correct --}}
                <input type="hidden" name="correct" value="0"> 

                {{-- Organizational Unit ID Field (Listener) --}}
                <div class="mb-4">
                    <label for="organizational_unit_id_new"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">الوحدة التنظيمية الجديدة:</label>
                    <select id="organizational_unit_id_new" name="organizational_unit_id" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                        <option value="">-- اختر وحدة --</option>
                        @foreach ($units as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organizational_unit_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Position ID Field (Target) --}}
                <div class="mb-4">
                    <label for="position_id_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">المسمى
                        الوظيفي الجديد:</label>

                    <select id="position_id_new" name="position_id" required disabled
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                        <option value="">-- اختر وحدة أولاً لتحميل المسميات الوظيفية --</option>
                    </select>
                    <p id="position_loading_message_new" class="text-sm text-gray-500 mt-1 hidden">جاري تحميل المسميات
                        الوظيفية...</p>
                    @error('position_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Date Field --}}
                <div class="mb-4">
                    <label for="start_date_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ
                        البدء:</label>
                    <input type="date" id="start_date_new" name="start_date"
                        value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('start_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-150 ease-in-out">
                    تسجيل التعيين/الترقية
                </button>
            </form>
            
            {{-- Optional: Link to the correction form --}}
            @if ($isAssigned)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin_users.position.edit', $user) }}" class="text-red-500 hover:text-red-700 text-sm font-medium underline">
                        لتصحيح بيانات السجل الحالي اضغط هنا.
                    </a>
                </div>
            @endif
        </div>

        {{-- JAVASCRIPT FOR DYNAMIC POSITION LOADING --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const unitSelect = document.getElementById('organizational_unit_id_new');
                const positionSelect = document.getElementById('position_id_new');
                const loadingMessage = document.getElementById('position_loading_message_new');
                const apiRoute = '{{ route('admin.api.positions_by_unit') }}';

                function loadPositions(unitId) {
                    positionSelect.innerHTML = '';
                    positionSelect.disabled = true;
                    loadingMessage.classList.remove('hidden');

                    if (!unitId) {
                        positionSelect.innerHTML =
                            '<option value="">-- اختر وحدة أولاً لتحميل المسميات الوظيفية --</option>';
                        loadingMessage.classList.add('hidden');
                        return;
                    }

                    fetch(`${apiRoute}?unit_id=${unitId}`)
                        .then(response => response.json())
                        .then(positions => {
                            positionSelect.innerHTML = '<option value="">-- اختر مسمى وظيفي --</option>';
                            
                            if (positions.length > 0) {
                                positions.forEach(position => {
                                    const option = document.createElement('option');
                                    option.value = position.id;
                                    option.textContent = position.title;
                                    positionSelect.appendChild(option);
                                });
                                positionSelect.disabled = false;
                            } else {
                                positionSelect.innerHTML =
                                    '<option value="">-- لا يوجد مسميات وظيفية متاحة في هذه الوحدة --</option>';
                                positionSelect.disabled = true;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching positions:', error);
                            positionSelect.innerHTML = '<option value="">فشل تحميل المسميات الوظيفية</option>';
                            positionSelect.disabled = true;
                        })
                        .finally(() => {
                            loadingMessage.classList.add('hidden');
                        });
                }

                unitSelect.addEventListener('change', function() {
                    loadPositions(this.value);
                });
            });
        </script>
    @endif
</div>