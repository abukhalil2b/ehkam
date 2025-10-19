<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <x-slot name="header">
        إضافة مشروع جديد للمؤشر: {{ $indicator->title }}
    </x-slot>
    <form action="{{ route('project.store',$indicator->id) }}" method="POST">
        @csrf

        <div class="container py-8 mx-auto px-4">
            <h3 class="mb-4 text-center text-green-700 text-lg font-bold">وزارة الأوقاف والشؤون الدينية</h3>

            <div class="p-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">القطاع</label>
                        <select name="sector_id"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled selected>اختر القطاع</option>
                            @foreach ($sectors as $sector)
                                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الدائرة</label>
                        <select
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled selected>اختر الدائرة</option>
                            <option> الحوكمة </option>
                            <option> التخطيط </option>
                            <option> القرآن</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                        <select
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled selected>اختر القسم</option>
                            <option>قسم التخطيط </option>
                            <option>قسم الإحصاء </option>
                            <option>قسم الجودة</option>
                        </select>
                    </div>
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
                    <input type="text"
                    name="title"
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

            <button
                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                حفظ المشروع
            </button>
        </div>
    </form>
</x-app-layout>
