<x-app-layout>
    <div class="max-w-3xl mx-auto mt-8 p-6">

        {{-- Header Section --}}
        <div class="flex items-center justify-between border-b pb-4 mb-6">
            <h2 class="text-3xl font-bold text-gray-800">
                مشاركة الاستبيان: <span class="text-indigo-600">{{ $questionnaire->title }}</span>
            </h2>
            <a href="{{ route('questionnaire.index') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-700 transition duration-150">
                &larr; العودة إلى القائمة
            </a>
        </div>

        {{-- Alert for Inactive Survey --}}
        @if (!$questionnaire->is_active)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">تنبيه!</p>
                <p>الاستبيان غير نشط حالياً. لن يتمكن المستجيبون من تعبئته.</p>
            </div>
        @endif

        {{-- Main Sharing Container --}}
        <div class="bg-white shadow-xl rounded-2xl p-8 space-y-8" x-data="{ 
            copyMessage: '', 
            accessUrl: '{{ $accessUrl }}'
        }">

            {{-- 1. Direct Link Section --}}
            <div class="border-b pb-6">
                <h3 class="text-xl font-semibold mb-3 text-gray-700">الرابط المباشر</h3>
                
                @if ($accessUrl)
                    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                        {{-- URL Display Field (Read-only) --}}
                        <input type="text" 
                               :value="accessUrl" 
                               readonly
                               class="flex-grow p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 text-gray-800 focus:ring-indigo-500 focus:border-indigo-500 text-left" 
                               dir="ltr">
                        
                        {{-- Copy Button (Alpine.js) --}}
                        <button @click="
                                navigator.clipboard.writeText(accessUrl);
                                copyMessage = 'تم نسخ الرابط!';
                                $nextTick(() => { setTimeout(() => copyMessage = '', 2000); });
                            "
                            class="flex-shrink-0 p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 text-sm font-medium w-full sm:w-auto">
                            <span x-text="copyMessage ? copyMessage : 'نسخ الرابط'">نسخ الرابط</span>
                        </button>
                    </div>

                    {{-- Feedback Message --}}
                    <p class="mt-2 text-sm text-gray-500">
                        @if ($questionnaire->target_response == 'open_for_all')
                            (هذا الرابط مخصص للوصول العام.)
                        @else
                            (هذا الرابط مخصص للمستخدمين المسجلين فقط.)
                        @endif
                    </p>
                @else
                    <p class="text-red-500 font-medium">
                        لا يمكن توليد رابط. الرجاء التأكد من إعدادات الاستبيان وتفعيله.
                    </p>
                @endif
            </div>

            {{-- 2. QR Code Section --}}
            <div class="pt-4 flex flex-col items-center">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">رمز الاستجابة السريع (QR Code)</h3>
                
                @if ($qrCode)
                    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg flex justify-center">
                        {{-- Display the SVG output directly using {!! !!} --}}
                        {{-- The package output is a string of SVG code --}}
                        {!! $qrCode !!} 
                    </div>
                    <p class="mt-4 text-sm text-gray-500 text-center">
                        يمكن مسح الرمز أعلاه مباشرة للوصول إلى الاستبيان.
                    </p>

                    {{-- Optional: Download Button (if you want to implement file download logic) --}}
                    {{-- This requires an extra controller method to serve the file --}}
                    {{-- <a href="#" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        تحميل صورة الرمز
                    </a> --}}

                @else
                    <div class="text-center p-4 bg-yellow-100 rounded-lg">
                        <p class="text-yellow-700 font-medium">
                            تعذر توليد الرمز. تأكد من أن الاستبيان نشط.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>