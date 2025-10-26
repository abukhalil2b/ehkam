<x-app-layout title="إنشاء مستخدم جديد">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header --}}
        <header class="p-4 border-b border-gray-100 bg-white rounded-t-lg">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-2">
                <span class="material-icons text-3xl text-indigo-600">person_add</span>
                إنشاء موظف جديد وتعيينه
            </h1>
            <p class="text-gray-500 mt-1 text-sm">أدخل بيانات الموظف الأساسية وتعيينه الأولي في الهيكل التنظيمي.</p>
        </header>

        {{-- Form --}}
        <div class="bg-white p-6 rounded-b-lg shadow-xl border border-t-0">
            <form action="{{ route('admin_users.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section 1: Basic Data --}}
                <div class="space-y-4 border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700">1. البيانات الشخصية وبيانات الدخول</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                            <input type="text" name="name" id="name" required
                                class="form-input w-full border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ old('name') }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                البريد الإلكتروني (يستخدم لتسجيل الدخول)
                            </label>
                            <input type="email" name="email" id="email" required
                                class="form-input w-full border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ old('email') }}">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                        افتراضيا  البريد الإلكتروني 
                        </div>

                    </div>
                </div>

                {{-- Section 2: Initial Assignment --}}
                <div class="space-y-4" x-data="positionSelector()">
                    <h2 class="text-lg font-semibold text-gray-700">2. التعيين الأولي (السجل الوظيفي)</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- UserType --}}
                        <div>
                            <label for="organizational_unit_id" class="block text-sm font-medium text-gray-700">
                               نوع الحساب
                            </label>
                            <select  name="user_type" class="form-select w-full border-gray-300 rounded-md shadow-sm p-2">
                                <option value="staff">حساب الموظف</option>
                            </select>
                        </div>

                        <div>
                            <label for="organizational_unit_id" class="block text-sm font-medium text-gray-700">
                                الوحدة التنظيمية
                            </label>
                            <select name="organizational_unit_id" id="organizational_unit_id" required
                                class="form-select w-full border-gray-300 rounded-md shadow-sm p-2"
                                x-model="selectedUnit">
                                <option value="">-- اختر وحدة --</option>
                                @foreach ($organizationalUnits as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('organizational_unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Position (Dynamic via API) --}}
                        <div>
                            <label for="position_id" class="block text-sm font-medium text-gray-700">
                                المسمى الوظيفي
                            </label>
                            <select name="position_id" id="position_id" required
                                class="form-select w-full border-gray-300 rounded-md shadow-sm p-2"
                                x-model="selectedPosition">
                                <option value="">-- اختر مسمى وظيفي --</option>

                                <template x-if="positions.length === 0 && selectedUnit">
                                    <option disabled>جاري التحميل...</option>
                                </template>

                                <template x-for="position in positions" :key="position.id">
                                    <option :value="position.id" x-text="position.title"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                تاريخ بدء العمل
                            </label>
                            <input type="date" name="start_date" id="start_date" required
                                class="form-input w-full border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ old('start_date', now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4 border-t">
                    <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition flex items-center justify-center">
                        <span class="material-icons text-lg -mt-1 rtl:ml-1">save</span>
                        حفظ وإنشاء المستخدم
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine.js script for dynamic position loading --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('positionSelector', () => ({
                positions: [],
                selectedUnit: '{{ old('organizational_unit_id') }}',
                selectedPosition: '{{ old('position_id') }}',

                async init() {
                    // Load positions initially if old value exists
                    if (this.selectedUnit) {
                        await this.loadPositions();
                    }

                    // Watch for changes in the unit dropdown
                    this.$watch('selectedUnit', async (value) => {
                        if (value) {
                            this.selectedPosition = '';
                            await this.loadPositions();
                        } else {
                            this.positions = [];
                        }
                    });
                },

                async loadPositions() {
                    if (!this.selectedUnit) {
                        this.positions = [];
                        return;
                    }

                    try {
                        const response = await fetch(`{{ route('admin.api.positions_by_unit') }}?unit_id=${this.selectedUnit}`);
                        const data = await response.json();
                        this.positions = data;

                        // Keep old selection if valid
                        if (this.selectedPosition && !data.find(p => p.id == this.selectedPosition)) {
                            this.selectedPosition = '';
                        }
                    } catch (error) {
                        console.error('Error fetching positions:', error);
                        this.positions = [];
                    }
                },
            }));
        });
    </script>
</x-app-layout>
