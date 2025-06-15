<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right" dir="rtl">
            المؤشرات
        </h2>
    </x-slot>
  
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8" dir="rtl">
        <div class="bg-white shadow-md rounded-lg p-6">
              <a href="{{ route('indicator.create') }}"
        class="w-44 flex items-center text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200 shadow-sm">
        <i class="fas fa-plus ml-2"></i>
        إضافة مؤشر جديد
    </a>
            @foreach ($indicators as $indicator)
                <div class="my-2 px-3 py-1.5 text-sm rounded-lg border border-gray-200">
                    <a href="{{ route('indicator.show', $indicator->id) }}"> {{ $indicator->title }}</a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
