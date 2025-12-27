<x-app-layout>
    <x-slot name="header">تعديل المشروع: {{ $project->title }}</x-slot>

    <form action="{{ route('project.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="container py-8 mx-auto px-4 max-w-4xl">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">

                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100 mb-6">
                    <h3 class="text-sm font-bold text-blue-800 mb-4">الجهة المسؤولة الحالية</h3>

                    <div class="flex gap-6 mb-4">
                        @foreach (['Directorate' => 'مديرية', 'Department' => 'دائرة', 'Section' => 'قسم'] as $value => $label)
                            <label class="inline-flex items-center">
                                <input type="radio" name="unit_level" value="{{ $value }}"
                                    onclick="toggleLevels('{{ $value }}')" class="form-radio text-blue-600">
                                <span class="mr-2 text-sm">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div id="directorate_container" class="hidden">
                            <label class="block text-xs font-medium text-gray-500 mb-1">المديرية</label>
                            <select id="directorate_select" onchange="fetchChildren(this.value, 'department_select')"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="" disabled selected>اختر المديرية</option>
                                @foreach ($sectors as $sector)
                                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="department_container" class="hidden">
                            <label class="block text-xs font-medium text-gray-500 mb-1">الدائرة</label>
                            <select id="department_select" onchange="fetchChildren(this.value, 'section_select')"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="" disabled selected>اختر الدائرة</option>
                            </select>
                        </div>

                        <div id="section_container" class="hidden">
                            <label class="block text-xs font-medium text-gray-500 mb-1">القسم</label>
                            <select id="section_select" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="" disabled selected>اختر القسم</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="organizational_unit_id" id="final_unit_id"
                        value="{{ $currentUnit->id ?? '' }}">
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المؤشر المرتبط</label>
                        <select name="indicator_id" class="w-full rounded-lg border-gray-300 text-sm">
                            @foreach ($indicators as $indicator)
                                <option value="{{ $indicator->id }}" @selected($indicator->id == $project->indicator_id)>
                                    {{ $indicator->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم المشروع</label>
                        <input type="text" name="title" value="{{ old('title', $project->title) }}"
                            class="w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 text-sm">{{ old('description', $project->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold">حفظ
                        التعديلات</button>
                    <a href="{{ route('project.show', $project) }}"
                        class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">إلغاء</a>
                </div>
            </div>
        </div>
    </form>

    {{-- إعادة استخدام نفس كود JavaScript السابق مع إضافة منطق الجلب --}}
    <script>
  // 1. تعريف الدالة خارج window.onload لتكون متاحة للـ HTML
async function fetchChildren(parentId, targetSelectId, selectedChildId = null) {
    if (!parentId) return;

    try {
        const response = await fetch(`/api/units/${parentId}/children`);
        const data = await response.json();
        const select = document.getElementById(targetSelectId);

        select.innerHTML = '<option value="" selected disabled>اختر...</option>';
        data.forEach(unit => {
            const option = document.createElement('option');
            option.value = unit.id;
            option.textContent = unit.name;
            // إذا كان هذا هو الابن المخزن سابقاً، نقوم بتحديده
            if (selectedChildId && unit.id == selectedChildId) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        // إذا قمنا باختيار دائرة، يجب أن نحفز جلب الأقسام التابعة لها تلقائياً
        if (selectedChildId && targetSelectId === 'department_select') {
            // سنحتاج لمعرفة ID القسم من الكنترولر (اختياري لتحسين التجربة)
        }
    } catch (error) {
        console.error('Error fetching units:', error);
    }
}

function toggleLevels(level) {
    const dContainer = document.getElementById('directorate_container');
    const deptContainer = document.getElementById('department_container');
    const secContainer = document.getElementById('section_container');

    dContainer.classList.add('hidden');
    deptContainer.classList.add('hidden');
    secContainer.classList.add('hidden');

    if (level === 'Directorate') dContainer.classList.remove('hidden');
    if (level === 'Department') { dContainer.classList.remove('hidden'); deptContainer.classList.remove('hidden'); }
    if (level === 'Section') { dContainer.classList.remove('hidden'); deptContainer.classList.remove('hidden'); secContainer.classList.remove('hidden'); }
}

// 2. منطق التشغيل عند تحميل الصفحة
window.onload = function() {
    @if ($currentUnit)
        const type = "{{ $currentUnit->type }}"; 
        const unitId = "{{ $currentUnit->id }}";

        // تفعيل الراديو وإظهار الحاويات
        const radio = document.querySelector(`input[value="${type}"]`);
        if(radio) {
            radio.checked = true;
            toggleLevels(type);
        }

        document.getElementById('final_unit_id').value = unitId;

        // منطق التعبئة التلقائية بناءً على نوع المالك الحالي
        @if($currentUnit->type == 'Directorate')
            document.getElementById('directorate_select').value = unitId;
        @elseif($currentUnit->type == 'Department')
            document.getElementById('directorate_select').value = "{{ $currentUnit->parent_id }}";
            fetchChildren("{{ $currentUnit->parent_id }}", 'department_select', unitId);
        @elseif($currentUnit->type == 'Section')
            // هنا نحتاج لمعرف "الجد" (المديرية) لإكمال السلسلة
            @php 
                $department = $currentUnit->parent;
                $directorateId = $department ? $department->parent_id : null;
            @endphp
            document.getElementById('directorate_select').value = "{{ $directorateId }}";
            fetchChildren("{{ $directorateId }}", 'department_select', "{{ $department->id }}");
            fetchChildren("{{ $department->id }}", 'section_select', unitId);
        @endif
    @endif
};

// تحديث الحقل المخفي عند أي تغيير يدوي بعد التحميل
document.addEventListener('change', function(e) {
    if (['directorate_select', 'department_select', 'section_select'].includes(e.target.id)) {
        document.getElementById('final_unit_id').value = e.target.value;
    }
});
    </script>
</x-app-layout>
