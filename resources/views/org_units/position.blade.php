<x-org-layout title="تعيين / ترقية موظف">
    <div class="max-w-4xl mx-auto">
        <!-- Info Card -->
        <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded-lg mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 text-2xl mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-2">حول التعيين والترقية:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>هذا النموذج يستخدم لتعيين موظف جديد في وظيفة أو ترقية موظف حالي</li>
                        <li>سيتم إغلاق السجل الوظيفي القديم تلقائياً وإنشاء سجل جديد</li>
                        <li>تأكد من اختيار تاريخ البدء الصحيح للتعيين الجديد</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.assign.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Select User -->
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        اختر الموظف <span class="text-red-500">*</span>
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                        <option value="">-- اختر الموظف --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    data-current-position="{{ $user->currentHistory->position->title ?? 'غير معين' }}"
                                    data-current-unit="{{ $user->currentHistory->OrgUnit->name ?? '-' }}"
                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Position Info -->
                <div id="currentPositionInfo" class="hidden bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-user-tie text-primary-700"></i>
                        <span>الوظيفة الحالية</span>
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">الوظيفة:</p>
                            <p class="font-semibold text-gray-800" id="currentPosition">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">الوحدة:</p>
                            <p class="font-semibold text-gray-800" id="currentUnit">-</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-arrow-circle-up text-green-600"></i>
                        <span>التعيين / الترقية الجديدة</span>
                    </h3>

                    <div class="space-y-6">
                        <!-- New Position -->
                        <div>
                            <label for="new_position_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                الوظيفة الجديدة <span class="text-red-500">*</span>
                            </label>
                            <select id="new_position_id" 
                                    name="new_position_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                <option value="">-- اختر الوظيفة --</option>
                                @foreach($allPositions as $position)
                                    <option value="{{ $position->id }}" {{ old('new_position_id') == $position->id ? 'selected' : '' }}>
                                        {{ $position->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('new_position_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Unit -->
                        <div>
                            <label for="new_unit_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                الوحدة التنظيمية
                            </label>
                            <select id="new_unit_id" 
                                    name="new_unit_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                <option value="">-- اختر الوحدة (اختياري) --</option>
                                @foreach($OrgUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ old('new_unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('new_unit_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                تاريخ البدء <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', date('Y-m-d')) }}"
                                   required
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-calendar-alt"></i>
                                تاريخ بدء الموظف في الوظيفة الجديدة
                            </p>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="bg-yellow-50 border-r-4 border-yellow-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold mb-1">تنبيه:</p>
                            <p>عند حفظ هذا التعيين، سيتم إغلاق السجل الوظيفي القديم للموظف تلقائياً. تأكد من صحة البيانات قبل الحفظ.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('org_unit.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>إلغاء</span>
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        <span>تأكيد التعيين / الترقية</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const userSelect = document.getElementById('user_id');
        const currentPositionInfo = document.getElementById('currentPositionInfo');
        const currentPosition = document.getElementById('currentPosition');
        const currentUnit = document.getElementById('currentUnit');

        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                currentPositionInfo.classList.remove('hidden');
                currentPosition.textContent = selectedOption.dataset.currentPosition || 'غير معين';
                currentUnit.textContent = selectedOption.dataset.currentUnit || '-';
            } else {
                currentPositionInfo.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-org-layout>