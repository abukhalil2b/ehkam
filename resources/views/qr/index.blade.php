<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12" x-data="{ openModal: false }">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-700 to-indigo-800 text-white shadow-lg mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ route('dashboard') }}"
                                class="text-purple-100 hover:text-white transition bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-100 border border-purple-500/30">
                                أدوات
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold tracking-tight">إدارة رموز QR</h1>
                        <p class="text-purple-100 mt-2 text-lg opacity-90">إنشاء وإدارة رموز الاستجابة السريعة للروابط
                            والمواقع.</p>
                    </div>
                    <div>
                        <button @click="openModal = true"
                            class="inline-flex items-center px-4 py-2 bg-white text-purple-700 font-bold rounded-lg shadow-md hover:bg-purple-50 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-purple-700 focus:ring-white">
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            إنشاء رمز جديد
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- List Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($qrCodes as $qr)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                        <div class="p-6 flex flex-col items-center text-center">
                            <div
                                class="mb-4 p-3 bg-gray-50 rounded-xl border border-gray-100 group-hover:border-purple-100 transition-colors">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->color(79, 70, 229)->generate($qr->content) !!}
                            </div>

                            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $qr->title }}</h3>
                            <a href="{{ $qr->content }}" target="_blank"
                                class="text-sm text-gray-500 hover:text-purple-600 transition-colors truncate max-w-full dir-ltr block mb-4">
                                {{ $qr->content }}
                            </a>

                            <div class="flex items-center gap-2 w-full mt-auto pt-4 border-t border-gray-50">
                                <a href="{{ route('qr.show', $qr) }}"
                                    class="flex-1 py-2 text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors text-center">
                                    عرض وتنزيل
                                </a>
                                <form action="{{ route('qr.destroy', $qr) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="حذف">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div
                            class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 text-center">
                            <div
                                class="w-20 h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 14.5v.01M12 13c0 5.523 4.477 10 10 10S22 17.523 22 12 17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد رموز QR</h3>
                            <p class="text-gray-500 mb-4">ابدأ بإنشاء أول رمز QR.</p>
                            <button @click="openModal = true"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold rounded-lg shadow-md hover:bg-purple-700 transition">
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                إنشاء رمز جديد
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Create Modal -->
        <div x-show="openModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="openModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="openModal = false"
                     aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="openModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    إنشاء رمز QR جديد
                                </h3>
                                <div class="mt-2 text-sm text-gray-500 mb-4">
                                    املأ البيانات التالية لإنشاء رمز استجابة سريعة جديد.
                                </div>
                                
                                <form action="{{ route('qr.store') }}" method="POST" id="createQrForm">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1 text-right">العنوان</label>
                                            <input type="text" name="title" id="title" required placeholder="مثال: موقع الشركة الرسمي" 
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1 text-right">الرابط (URL)</label>
                                            <input type="url" name="content" id="content" required placeholder="https://example.com" 
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" onclick="document.getElementById('createQrForm').submit()" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            إنشاء
                        </button>
                        <button type="button" @click="openModal = false" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>