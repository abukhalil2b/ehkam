<x-app-layout>
<div dir="rtl" class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">مشاريع SWOT</h1>
            <a 
                href="{{ route('swot.create') }}"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            >
                إنشاء مشروع جديد
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $project->title }}</h2>
                        @if($project->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                نشط
                            </span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded">
                                غير نشط
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="text-gray-800">{{ $project->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">عدد العناصر:</span>
                            <span class="text-gray-800 font-semibold">{{ $project->boards->count() }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <div class="text-center">
                            <p class="text-xs text-green-600">S</p>
                            <p class="text-lg font-bold text-green-700">
                                {{ $project->boards->where('type', 'strength')->count() }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-red-600">W</p>
                            <p class="text-lg font-bold text-red-700">
                                {{ $project->boards->where('type', 'weakness')->count() }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-blue-600">O</p>
                            <p class="text-lg font-bold text-blue-700">
                                {{ $project->boards->where('type', 'opportunity')->count() }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-yellow-600">T</p>
                            <p class="text-lg font-bold text-yellow-700">
                                {{ $project->boards->where('type', 'threat')->count() }}
                            </p>
                        </div>
                    </div>

                    <a 
                        href="{{ route('swot.admin', $project->id) }}"
                        class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        عرض اللوحة
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مشاريع SWOT بعد</h3>
                    <p class="text-gray-600 mb-6">ابدأ بإنشاء أول مشروع تحليل SWOT لك.</p>
                    <a 
                        href="{{ route('swot.create') }}"
                        class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        إنشاء مشروع
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>
