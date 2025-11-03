<x-app-layout>

    <div class="max-w-4xl mx-auto mt-6 space-y-4">
        <h2 class="text-xl font-bold">الاستبيانات</h2>
        <a href="{{ route('questionnaire.create') }}"
            class="p-3 w-44 flex items-center justify-center text-white bg-green-800 rounded-lg text-sm px-5">
            جديد
        </a>

        @if (session('success'))
            {{-- Simple success notification for the copy feature --}}
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @foreach ($questionnaires as $q)
            {{-- Initialize Alpine component for copy feature --}}
            <div class="p-6 bg-white rounded-2xl shadow" x-data="{ copyMessage: '', urlToCopy: '' }">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $q->title }}</h3>

                        {{-- Determine the URL and text based on the target response type --}}
                        @php
                            $accessUrl = '';
                            $accessText = '';
                            if ($q->target_response == 'open_for_all' && $q->public_hash) {
                                $accessUrl = route('questionnaire.public_take', $q->public_hash, false); // false for relative URL
                                $accessText = 'مفتوح للكل';
                            } elseif ($q->target_response == 'registerd_only') {
                                $accessUrl = route('questionnaire.registered_take', $q, false);
                                $accessText = 'فقط المسجلين';
                            }
                        @endphp

                        <div class="text-gray-600">{{ $accessText }}</div>

                        <span class="flex items-center gap-1 text-sm text-gray-500">
                            @if ($q->is_active)
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                نشط
                            @else
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                غير نشط
                            @endif
                        </span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col items-end gap-2">
                        <div class="flex gap-2">
                            <a href="{{ route('questionnaire.show', $q) }}"
                                class="text-orange-600 font-semibold text-sm">إدارة</a>
                            @if ($q->target_response == 'open_for_all')
                                <a href="{{ route('questionnaire.public_result', $q) }}"
                                    class="text-blue-600 font-semibold text-sm">نتائج العام</a>
                                @if ($q->is_active)
                                    <a href="{{ route('questionnaire.share_link', $q) }}"
                                        class="text-purple-600 font-semibold text-sm">مشاركة/رابط</a>
                                @endif
                            @endif

                            {{-- Existing direct access links (Optional but useful for quick testing) --}}
                            @if ($q->target_response === 'open_for_all' && $q->public_hash)
                                <a href="{{ route('questionnaire.public_take', $q->public_hash) }}"
                                    class="text-blue-600 font-semibold text-sm">تعبئة (عام)</a>
                            @elseif ($q->target_response === 'registerd_only')
                                <a href="{{ route('questionnaire.registered_take', $q) }}"
                                    class="text-blue-600 font-semibold text-sm">تعبئة (مسجل)</a>
                            @else
                                <span class="text-gray-500 text-sm">غير متاح</span>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- QR Code Modal (Requires a basic Alpine/Tailwind modal component) --}}
                <div x-data="{ open: false }"
                    @open-modal.window="if ($event.detail === 'qr-code-{{ $q->id }}') open = true" x-show="open"
                    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        {{-- Background overlay --}}
                        <div x-show="open" x-transition.opacity @click="open = false"
                            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

                        {{-- Modal Content --}}
                        <div x-show="open" x-transition.duration.300ms
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-right w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                            رمز QR للاستبيان
                                        </h3>

                                        {{-- Google Charts QR Code Generator --}}
                                        <div class="flex justify-center">
                                            {{-- URL needs to be full URL for the QR code to work universally --}}
                                            @php
                                                $fullUrl = url($accessUrl);
                                                // Using Google Charts API for simple QR code generation
                                                $qrCodeUrl =
                                                    'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' .
                                                    urlencode($fullUrl);
                                            @endphp
                                            <img src="{{ $qrCodeUrl }}" alt="QR Code for {{ $q->title }}"
                                                class="w-64 h-64 border p-2">
                                        </div>

                                        <p class="text-sm text-gray-500 mt-4 break-all text-left" dir="ltr">
                                            {{ $fullUrl }}</p>

                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" @click="open = false"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    إغلاق
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
