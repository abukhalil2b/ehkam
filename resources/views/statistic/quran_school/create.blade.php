<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">إضافة إحصائية جديدة لمدارس القرآن الكريم</h2>

                <form method="POST" action="{{ route('quran-schools.store') }}" 
                      x-data="locationData({{ $governorates->toJson() }})">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">السنة</label>
                            <input type="number" name="year" value="{{ old('year', date('Y')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">المحافظة</label>
                            <select name="governorate_id" x-model="selectedGov" @change="updateWilayats()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">اختر المحافظة...</option>
                                <template x-for="gov in governorates" :key="gov.id">
                                    <option :value="gov.id" x-text="gov.name"></option>
                                </template>
                            </select>
                            @error('governorate_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">الولاية (اختياري)</label>
                            <select name="wilayat_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">إحصائية شاملة للمحافظة</option>
                                <template x-for="wilayat in wilayats" :key="wilayat.id">
                                    <option :value="wilayat.id" x-text="wilayat.name"></option>
                                </template>
                            </select>
                            @error('wilayat_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700 mb-4">بيانات المدارس والكوادر</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700">عدد مدارس القرآن الكريم</label>
                            <input type="number" name="schools_count" value="{{ old('schools_count', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('schools_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800 mb-3 border-b border-blue-200 pb-2">الطلبة الدارسين</h4>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">ذكور</label>
                                    <input type="number" name="students_male" value="{{ old('students_male', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">إناث</label>
                                    <input type="number" name="students_female" value="{{ old('students_female', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-emerald-50 p-4 rounded-lg">
                            <h4 class="font-medium text-emerald-800 mb-3 border-b border-emerald-200 pb-2">الكادر التدريسي (المعلمين)</h4>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">ذكور</label>
                                    <input type="number" name="teachers_male" value="{{ old('teachers_male', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">إناث</label>
                                    <input type="number" name="teachers_female" value="{{ old('teachers_female', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            حفظ البيانات
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function locationData(governorates) {
            return {
                governorates: governorates,
                selectedGov: '{{ old('governorate_id') }}',
                wilayats: [],
                init() {
                    if (this.selectedGov) {
                        this.updateWilayats();
                    }
                },
                updateWilayats() {
                    if (!this.selectedGov) {
                        this.wilayats = [];
                        return;
                    }
                    let gov = this.governorates.find(g => g.id == this.selectedGov);
                    this.wilayats = gov ? gov.wilayats : [];
                }
            }
        }
    </script>
</x-app-layout>