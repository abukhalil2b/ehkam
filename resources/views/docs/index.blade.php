@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 التوثيق التقني</h1>
                <p class="text-lg text-gray-600">دليل شامل لفهم النظام والعمل عليه</p>
            </div>

            @if(count($docs) === 0)
                <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد وثائق متاحة</h3>
                    <p class="text-gray-600">سيتم عرض ملفات التوثيق هنا عند إضافتها إلى مجلد <code
                            class="bg-gray-100 px-2 py-1 rounded">docs/</code></p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($docs as $doc)
                        <a href="{{ route('docs.show', $doc['slug']) }}" class="block group">
                            <div
                                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 p-6 h-full border-r-4 border-blue-500 hover:border-blue-600">
                                <div class="flex items-start justify-between mb-3">
                                    <h2 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $doc['title'] }}
                                    </h2>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors flex-shrink-0 mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>

                                @if($doc['description'])
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $doc['description'] }}</p>
                                @endif

                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>آخر تحديث:
                                        {{ \Carbon\Carbon::createFromTimestamp($doc['modified'])->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Quick Links -->
            <div class="mt-12 bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">📖 روابط سريعة</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-blue-700 hover:text-blue-900">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        الرئيسية
                    </a>
                    <a href="{{ route('admin.workflows.index') }}"
                        class="flex items-center text-blue-700 hover:text-blue-900">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        إدارة سير العمل
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection