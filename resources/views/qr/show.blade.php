<x-app-layout title="{{ $qr->title }}">
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6">
        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 max-w-md w-full text-center">

            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $qr->title }}</h1>

            <div class="bg-gray-50 p-6 rounded-2xl mb-6 inline-block border border-gray-200">
                {!! $qrImage !!}
            </div>

            <div class="mb-8">
                <a href="{{ $qr->content }}" target="_blank"
                    class="text-purple-600 hover:text-purple-800 font-medium break-all dir-ltr block">
                    {{ $qr->content }}
                </a>
            </div>

            <div class="flex gap-3 justify-center">
                <a href="{{ route('qr.index') }}"
                    class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                    عودة للقائمة
                </a>
                <button onclick="window.print()"
                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    طباعة
                </button>
            </div>
        </div>
    </div>
</x-app-layout>