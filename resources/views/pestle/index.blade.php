<x-app-layout>
    <div dir="rtl" class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            {{-- Instruction Guide --}}
            <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-6 mb-8 shadow-sm">
                <h3 class="font-bold text-indigo-900 text-lg mb-4 flex items-center">
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    دليل الاستخدام السريع (PESTLE)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="bg-white p-4 rounded-lg border border-indigo-100 shadow-sm">
                        <div
                            class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold mb-3">
                            1</div>
                        <h4 class="font-bold text-gray-900 mb-1">إنشاء المشروع</h4>
                        <p class="text-sm text-gray-600">قم بإنشاء مشروع جديد لتحليل البيئة الخارجية (PESTLE).</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white p-4 rounded-lg border border-indigo-100 shadow-sm">
                        <div
                            class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold mb-3">
                            2</div>
                        <h4 class="font-bold text-gray-900 mb-1">شاشة العرض</h4>
                        <p class="text-sm text-gray-600">افتح "شاشة العرض" واعرض كود QR للمشاركين.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white p-4 rounded-lg border border-indigo-100 shadow-sm">
                        <div
                            class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold mb-3">
                            3</div>
                        <h4 class="font-bold text-gray-900 mb-1">المشاركة والتفاعل</h4>
                        <p class="text-sm text-gray-600">يضيف المشاركون العوامل السياسية، الاقتصادية، الاجتماعية،
                            التقنية... الخ</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white p-4 rounded-lg border border-indigo-100 shadow-sm">
                        <div
                            class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold mb-3">
                            4</div>
                        <h4 class="font-bold text-gray-900 mb-1">التحليل والإنهاء</h4>
                        <p class="text-sm text-gray-600">ناقش النتائج ثم اضغط "إنهاء المشروع" لكتابة الاستراتيجيات.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">مشاريع PESTLE</h1>
                <a href="{{ route('pestle.create') }}"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
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
                                <span class="text-gray-800 font-semibold">{{ $project->items->count() }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-6 gap-1 mb-4 text-center text-[10px]">
                            <div>
                                <p class="text-red-600 font-bold">P</p>
                                <p>{{ $project->items->where('type', 'political')->count() }}</p>
                            </div>
                            <div>
                                <p class="text-blue-600 font-bold">E</p>
                                <p>{{ $project->items->where('type', 'economic')->count() }}</p>
                            </div>
                            <div>
                                <p class="text-yellow-600 font-bold">S</p>
                                <p>{{ $project->items->where('type', 'social')->count() }}</p>
                            </div>
                            <div>
                                <p class="text-purple-600 font-bold">T</p>
                                <p>{{ $project->items->where('type', 'technological')->count() }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-bold">L</p>
                                <p>{{ $project->items->where('type', 'legal')->count() }}</p>
                            </div>
                            <div>
                                <p class="text-emerald-600 font-bold">E</p>
                                <p>{{ $project->items->where('type', 'environmental')->count() }}</p>
                            </div>
                        </div>

                        <a href="{{ route('pestle.admin', $project->id) }}"
                            class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            عرض اللوحة
                        </a>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مشاريع PESTLE بعد</h3>
                        <p class="text-gray-600 mb-6">ابدأ بإنشاء أول مشروع تحليل PESTLE لك.</p>
                        <a href="{{ route('pestle.create') }}"
                            class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            إنشاء مشروع
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>