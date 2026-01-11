<x-org-layout title="إضافة وظيفة جديدة">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.position.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Position Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        المسمى الوظيفي <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                           placeholder="مثال: مدير عام الموارد البشرية">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Organizational Unit -->
                <div>
                    <label for="org_unit_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        الوحدة التنظيمية <span class="text-red-500">*</span>
                    </label>
                    <select id="org_unit_id" 
                            name="org_unit_id" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                        <option value="">-- اختر الوحدة التنظيمية --</option>
                        @foreach($OrgUnits as $unit)
                            <option value="{{ $unit->id }}" {{ old('org_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ $unit->type }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle"></i>
                        اختر الوحدة التنظيمية التي تنتمي إليها هذه الوظيفة
                    </p>
                    @error('org_unit_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reports To Position -->
                <div>
                    <label for="reports_to_position_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        تقرير إلى (اختياري)
                    </label>
                    <select id="reports_to_position_id" 
                            name="reports_to_position_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                        <option value="">-- لا تقرير لأحد (وظيفة عليا) --</option>
                        @foreach($allPositions as $position)
                            <option value="{{ $position->id }}" {{ old('reports_to_position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle"></i>
                        حدد الوظيفة التي تقرير إليها هذه الوظيفة في التسلسل الإداري
                    </p>
                    @error('reports_to_position_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordered (Priority) -->
                <div>
                    <label for="ordered" class="block text-sm font-semibold text-gray-700 mb-2">
                        الترتيب
                    </label>
                    <input type="number" 
                           id="ordered" 
                           name="ordered" 
                           value="{{ old('ordered', 0) }}"
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle"></i>
                        رقم الترتيب لعرض الوظيفة (0 = الافتراضي)
                    </p>
                    @error('ordered')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-lightbulb text-blue-500 text-xl mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">ملاحظات:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>سيتم توليد رمز الوظيفة (job_code) تلقائياً</li>
                                <li>يمكنك ربط الوظيفة بوحدة تنظيمية محددة</li>
                                <li>التسلسل الإداري يحدد علاقة الإشراف بين الوظائف</li>
                                <li>بعد إنشاء الوظيفة، يمكنك تعيين الموظفين لها</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('admin_position.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>إلغاء</span>
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-primary-800 transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>حفظ الوظيفة</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6" id="previewSection" style="display: none;">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-eye text-primary-700"></i>
                <span>معاينة</span>
            </h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="space-y-3">
                    <div class="flex items-start gap-3 pb-3 border-b border-gray-200">
                        <i class="fas fa-briefcase text-primary-700 mt-1"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">المسمى الوظيفي:</p>
                            <p class="font-bold text-lg text-gray-800" id="previewTitle">-</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-gray-200">
                        <i class="fas fa-building text-primary-700 mt-1"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">الوحدة التنظيمية:</p>
                            <p class="font-semibold text-gray-800" id="previewUnit">-</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-arrow-up text-primary-700 mt-1"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">تقرير إلى:</p>
                            <p class="font-semibold text-gray-800" id="previewReportsTo">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const titleInput = document.getElementById('title');
        const unitSelect = document.getElementById('org_unit_id');
        const reportsToSelect = document.getElementById('reports_to_position_id');
        const previewSection = document.getElementById('previewSection');

        function updatePreview() {
            const title = titleInput.value;
            const unitText = unitSelect.options[unitSelect.selectedIndex].text;
            const reportsToText = reportsToSelect.options[reportsToSelect.selectedIndex].text;
            
            if (title || unitSelect.value) {
                previewSection.style.display = 'block';
                document.getElementById('previewTitle').textContent = title || '-';
                document.getElementById('previewUnit').textContent = unitSelect.value ? unitText : '-';
                document.getElementById('previewReportsTo').textContent = reportsToSelect.value ? reportsToText : 'لا تقرير لأحد (وظيفة عليا)';
            } else {
                previewSection.style.display = 'none';
            }
        }

        titleInput.addEventListener('input', updatePreview);
        unitSelect.addEventListener('change', updatePreview);
        reportsToSelect.addEventListener('change', updatePreview);
    </script>
    @endpush
</x-org-layout>