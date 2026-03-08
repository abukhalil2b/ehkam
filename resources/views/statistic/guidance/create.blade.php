<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">إضافة إحصائية وعظ وإرشاد جديدة</h2>

                <form method="POST" action="{{ route('guidance-statistics.store') }}" 
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

                    <h3 class="text-lg font-semibold text-gray-700 mb-4">الكوادر الأساسية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">الأئمة والخطباء</label>
                            <input type="number" name="imams_and_preachers_count" value="{{ old('imams_and_preachers_count', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">المؤذنون</label>
                            <input type="number" name="muezzins_count" value="{{ old('muezzins_count', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700 mb-4">تفصيل الكوادر الدينية (ذكور / إناث)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">الموجهون (ذكور)</label>
                                <input type="number" name="mentors_male" value="{{ old('mentors_male', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">الموجهون (إناث)</label>
                                <input type="number" name="mentors_female" value="{{ old('mentors_female', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">الوعاظ (ذكور)</label>
                                <input type="number" name="preachers_male" value="{{ old('preachers_male', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">الوعاظ (إناث)</label>
                                <input type="number" name="preachers_female" value="{{ old('preachers_female', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">المرشدون (ذكور)</label>
                                <input type="number" name="religious_guides_male" value="{{ old('religious_guides_male', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">المرشدون (إناث)</label>
                                <input type="number" name="religious_guides_female" value="{{ old('religious_guides_female', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">المشرفون (ذكور)</label>
                                <input type="number" name="supervisors_male" value="{{ old('supervisors_male', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-medium text-gray-700">المشرفون (إناث)</label>
                                <input type="number" name="supervisors_female" value="{{ old('supervisors_female', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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