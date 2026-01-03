<x-app-layout>
    <x-slot name="header">
        إضافة مشروع جديد للمؤشر: {{ $indicator->title }}
    </x-slot>
    <form action="{{ route('project.store') }}" method="POST">
        @csrf
        <div class="container py-8 mx-auto px-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">المنفذ</label>
                <select
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="" selected disabled>اختر المنفذ...</option>
                    @foreach ($executors as $executor)
                        <option value="{{ $executor->id }}">{{ $executor->name }}</option>
                    @endforeach
                </select>
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
            <input type="hidden" name="indicator_id" value="{{ $indicator->id }}">
            <button
                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                حفظ المشروع
            </button>
        </div>
    </form>

</x-app-layout>
