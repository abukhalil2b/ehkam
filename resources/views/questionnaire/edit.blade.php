<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-white shadow rounded-2xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">تعديل الاستبيان</h2>

        <form action="{{ route('questionnaire.update', $questionnaire->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">العنوان</label>
                    <input type="text" name="title" value="{{ old('title', $questionnaire->title) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- is_active checkbox -->
                <input type="hidden" name="is_active" value="0">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1"
                        {{ $questionnaire->is_active ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">مفعل</span>
                </div>
            </div>

            <div class="text-left">
                <x-primary-button>تحديث بيانات الاستبيان</x-primary-button>
            </div>
        </form>
    </div>

</x-app-layout>
