<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تعديل المسابقة') }}
            </h2>
            <a href="{{ route('admin.competitions.show', $competition) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-150">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                العودة للمسابقة
            </a>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-right" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-right" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-reverse space-x-3">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                @if($competition->status === 'closed') bg-gray-100 text-gray-800
                                @elseif($competition->status === 'started') bg-green-100 text-green-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ $competition->status === 'closed' ? 'مغلقة' : ($competition->status === 'started' ? 'بدأت' : 'منتهية') }}
                            </span>
                            <span class="text-sm text-gray-600">
                                الرمز: <span class="font-mono font-bold text-gray-900">{{ $competition->join_code }}</span>
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            أُنشئت {{ $competition->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.competitions.update', $competition) }}" 
                      method="POST" 
                      class="p-6 text-right"
                      x-data="{ 
                          title: '{{ old('title', $competition->title) }}',
                          titleLength: {{ strlen(old('title', $competition->title)) }}
                      }">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            عنوان المسابقة
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                required
                                maxlength="255"
                                x-model="title"
                                @input="titleLength = $event.target.value.length"
                                value="{{ old('title', $competition->title) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('title') border-red-500 @enderror text-right"
                                placeholder="مثال: مسابقة المعلومات العامة 2025">
                            <div class="absolute left-3 top-3 text-xs text-gray-400" x-text="`${titleLength}/255`"></div>
                        </div>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center justify-end">
                                {{ $message }}
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            اختر عنواناً واضحاً ووصفياً لمسابقتك
                        </p>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            تفاصيل المسابقة
                        </h3>
                        <div class="space-y-2 text-sm text-blue-800">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>تمت إضافة <strong>{{ $competition->questions()->count() }}</strong> سؤال</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>تم تسجيل <strong>{{ $competition->participants()->count() }}</strong> مشارك</span>
                            </div>
                            @if($competition->status !== 'closed')
                                <div class="flex items-start mt-3 pt-3 border-t border-blue-300">
                                    <svg class="w-4 h-4 ml-2 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-xs">ملاحظة: التعديل محدود لأن المسابقة {{ $competition->status === 'started' ? 'بدأت بالفعل' : 'انتهت' }}. يمكن تغيير العنوان فقط.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($competition->status !== 'closed')
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h3 class="font-semibold text-yellow-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                وضع التعديل المحدود
                            </h3>
                            <p class="text-sm text-yellow-800">
                                هذه المسابقة {{ $competition->status === 'started' ? 'بدأت بالفعل' : 'انتهت' }}. 
                                للحفاظ على سلامة البيانات، يمكنك فقط تعديل العنوان. الأسئلة والإعدادات الأخرى مقفلة.
                            </p>
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.competitions.show', $competition) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition duration-150">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            إلغاء
                        </a>

                        <div class="flex items-center space-x-reverse space-x-3">
                            @if($competition->status === 'closed')
                                <button 
                                    type="button"
                                    onclick="if(confirm('هل أنت متأكد من رغبتك في حذف هذه المسابقة؟ لا يمكن التراجع عن هذا الإجراء.')) { document.getElementById('delete-form').submit(); }"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-150">
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    حذف المسابقة
                                </button>
                            @endif

                            <button 
                                type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if($competition->status === 'closed')
                <form id="delete-form" 
                      action="{{ route('admin.competitions.destroy', $competition) }}" 
                      method="POST" 
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-right">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center justify-end">
                        نصائح للتعديل
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start justify-end">
                            استخدم عناوين واضحة ووصفية
                            <svg class="w-4 h-4 ml-2 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </li>
                        <li class="flex items-start justify-end">
                            اجعل العناوين جذابة للمشاركين
                            <svg class="w-4 h-4 ml-2 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </li>
                        <li class="flex items-start justify-end">
                            قم بتضمين الموضوع أو مستوى الصعوبة في العنوان
                            <svg class="w-4 h-4 ml-2 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </li>
                    </ul>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center justify-end">
                        إجراءات سريعة
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.competitions.show', $competition) }}" 
                           class="flex items-center justify-end text-sm text-blue-600 hover:text-blue-800 transition">
                            عرض تفاصيل المسابقة
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @if($competition->status === 'finished')
                            <a href="{{ route('admin.competitions.results', $competition) }}" 
                               class="flex items-center justify-end text-sm text-blue-600 hover:text-blue-800 transition">
                                عرض النتائج والتحليلات
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('admin.competitions.index') }}" 
                           class="flex items-center justify-end text-sm text-blue-600 hover:text-blue-800 transition">
                            العودة لجميع المسابقات
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>