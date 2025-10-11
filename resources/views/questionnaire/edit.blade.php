<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-white shadow rounded-2xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">تعديل الاستبيان</h2>

        <form action="{{ route('questionnaire.update', $questionnaire->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                
                {{-- 1. Title --}}
                <div>
                    <label for="title" class="block font-semibold text-gray-700 mb-2">العنوان</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $questionnaire->title) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="border p-4 rounded-lg bg-gray-50">
                    <label class="block font-semibold text-gray-700 mb-3">جمهور المستهدف</label>
                    <div class="space-y-3">
                        
                        <div class="flex items-center gap-2">
                            <input type="radio" name="target_response" id="target_registered" value="registerd_only"
                                {{ old('target_response', $questionnaire->target_response) == 'registerd_only' ? 'checked' : '' }}
                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500" required>
                            <label for="target_registered" class="text-gray-700 font-medium">
                                للمستخدمين المسجلين فقط
                                <span class="block text-sm text-gray-500 font-normal">يتطلب تسجيل الدخول للوصول</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="radio" name="target_response" id="target_public" value="open_for_all"
                                {{ old('target_response', $questionnaire->target_response) == 'open_for_all' ? 'checked' : '' }}
                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="target_public" class="text-gray-700 font-medium">
                                مفتوح للعامة
                                <span class="block text-sm text-gray-500 font-normal">يمكن الوصول عبر رابط عام (Hash) بدون تسجيل</span>
                            </label>
                        </div>

                    </div>
                </div>


               <div>
    <label for="is_active" class="block font-semibold text-gray-700 mb-2">حالة الاستبيان</label>
    <select name="is_active" id="is_active" 
        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        required>
        
        <option value="1" 
            {{ old('is_active', $questionnaire->is_active) == 1 ? 'selected' : '' }}>
            مفعل (نشط)
        </option>
        
        <option value="0" 
            {{ old('is_active', $questionnaire->is_active) == 0 ? 'selected' : '' }}>
            غير مفعل (معطل)
        </option>
        
    </select>
</div>
            </div>

            <div class="text-left pt-4 border-t">
                <x-primary-button>تحديث بيانات الاستبيان</x-primary-button>
            </div>
        </form>
    </div>

</x-app-layout>