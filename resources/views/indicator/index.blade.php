<x-app-layout>

    <x-slot name="header">
        إدارة المؤشرات
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8" dir="rtl">
        <a href="{{ route('indicator.create') }}"
            class="py-4 w-44 flex items-center text-white bg-green-800 rounded-lg text-sm px-5">
            <i class="fas fa-plus ml-2"></i>
            إضافة مؤشر جديد
        </a>

        @foreach ($indicators as $indicator)
            <div class="mt-4 bg-white shadow-md rounded-lg p-6">
                <div>
                    {{ $indicator->title }}
                </div>

                <div class="mt-4 flex gap-1">
                    <a class="w-32 flex items-center justify-center text-white bg-green-500 rounded-lg text-sm p-1 "
                        href="{{ route('indicator.show', $indicator->id) }}">بيانات المؤشر</a>

                    <a class="w-32 flex items-center justify-center text-white bg-green-500 rounded-lg text-sm p-1 "
                        href="{{ route('indicator.target', $indicator->id) }}">المستهدف</a>

                    <a class="w-32 flex items-center justify-center text-white bg-green-500 rounded-lg text-sm p-1 "
                        href="{{ route('indicator.achieved', $indicator->id) }}">المحقق</a>

                    <a class="w-32 flex items-center justify-center text-white bg-green-500 rounded-lg text-sm p-1 "
                        href="{{ route('project.index', ['status' => 'Draft']) }}">إدارة المشاريع</a>
                </div>
            </div>
        @endforeach
    </div>

</x-app-layout>
