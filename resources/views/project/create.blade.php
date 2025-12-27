<x-app-layout>
    <x-slot name="header">
        إضافة مشروع جديد للمؤشر: {{ $indicator->title }}
    </x-slot>
    <form action="{{ route('project.store') }}" method="POST">
        @csrf

        <div class="container py-8 mx-auto px-4">

            <div class="p-3">
                <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">تحديد الجهة المالكة للمشروع</h3>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">مستوى الجهة المسؤولة:</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="unit_level" value="Directorate"
                                    onclick="toggleLevels('Directorate')" class="form-radio text-blue-600">
                                <span class="mr-2 text-sm">مديرية عامة (رأس الهيكل)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="unit_level" value="Department"
                                    onclick="toggleLevels('Department')" class="form-radio text-blue-600">
                                <span class="mr-2 text-sm">دائرة</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="unit_level" value="Section"
                                    onclick="toggleLevels('Section')" class="form-radio text-blue-600">
                                <span class="mr-2 text-sm">قسم</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div id="directorate_container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">المديرية العامة</label>
                            <select id="directorate_select" onchange="fetchChildren(this.value, 'department_select')"
                                class="w-full bg-white border border-gray-300 rounded-lg p-2.5">
                                <option value="" selected disabled>اختر المديرية...</option>
                                @foreach ($sectors as $sector)
                                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="department_container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">الدائرة</label>
                            <select id="department_select" onchange="fetchChildren(this.value, 'section_select')"
                                class="w-full bg-white border border-gray-300 rounded-lg p-2.5">
                                <option value="" selected disabled>اختر الدائرة...</option>
                            </select>
                        </div>

                        <div id="section_container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                            <select id="section_select" class="w-full bg-white border border-gray-300 rounded-lg p-2.5">
                                <option value="" selected disabled>اختر القسم...</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="organizational_unit_id" id="final_unit_id">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع المشروع</label>
                    <select
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="" disabled selected>اختر نوع المشروع</option>
                        <option>مشروع</option>
                        <option>مبادرة تمكينية</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                    <input type="text" name="title"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                        placeholder="أدخل اسم المشروع">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                    <textarea rows="4"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                        placeholder="أدخل وصف المشروع"></textarea>
                </div>
            </div>
            <input type="hidden" name="indicator_id" value="{{ $indicator->id }}">
            <button
                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                حفظ المشروع
            </button>
        </div>
    </form>

    <script>
        // دالة للتحكم في ظهور واختفاء الحاويات بناءً على الراديو المختار
        function toggleLevels(level) {
            const dContainer = document.getElementById('directorate_container');
            const deptContainer = document.getElementById('department_container');
            const secContainer = document.getElementById('section_container');
            const finalInput = document.getElementById('final_unit_id');

            // إعادة ضبط القوائم والحقل المخفي
            finalInput.value = "";
            resetSelect('department_select');
            resetSelect('section_select');

            // إخفاء الكل
            dContainer.classList.add('hidden');
            deptContainer.classList.add('hidden');
            secContainer.classList.add('hidden');

            // إظهار المطلوب
            if (level === 'Directorate') {
                dContainer.classList.remove('hidden');
            } else if (level === 'Department') {
                dContainer.classList.remove('hidden');
                deptContainer.classList.remove('hidden');
            } else if (level === 'Section') {
                dContainer.classList.remove('hidden');
                deptContainer.classList.remove('hidden');
                secContainer.classList.remove('hidden');
            }
        }

        // دالة لجلب الأبناء عبر Ajax
        async function fetchChildren(parentId, targetSelectId) {
            if (!parentId) return;

            const targetSelect = document.getElementById(targetSelectId);
            const finalInput = document.getElementById('final_unit_id');

            // تحديث القيمة النهائية مؤقتاً (في حال توقف المستخدم عند هذا المستوى)
            finalInput.value = parentId;

            try {
                const response = await fetch(`/api/units/${parentId}/children`);
                const data = await response.json();

                targetSelect.innerHTML = '<option value="" selected disabled>اختر من القائمة...</option>';
                data.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = unit.name;
                    targetSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching units:', error);
            }
        }

        // تحديث القيمة النهائية عند تغيير أي اختيار
        document.addEventListener('change', function(e) {
            if (['directorate_select', 'department_select', 'section_select'].includes(e.target.id)) {
                document.getElementById('final_unit_id').value = e.target.value;
            }
        });

        // دالة مساعدة لتفريغ القوائم
        function resetSelect(id) {
            const select = document.getElementById(id);
            if (select) select.innerHTML = '<option value="" selected disabled>اختر...</option>';
        }
    </script>
</x-app-layout>
