<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Help / Info Section --}}
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4 mb-6 shadow-sm">
            <h3 class="font-bold text-amber-800 text-lg mb-2 flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                دليل إدارة الاستبيانات
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-amber-900">
                <div class="bg-white p-3 rounded-lg border border-amber-100">
                    <span class="font-bold block mb-1 text-amber-600">1. إنشاء الاستبيان</span>
                    أنشئ استبياناً جديداً وحدد الأسئلة وأنواعها (نصي، اختيارات، تقييم).
                </div>
                <div class="bg-white p-3 rounded-lg border border-amber-100">
                    <span class="font-bold block mb-1 text-amber-600">2. النشر والمشاركة</span>
                    شارك رابط الاستبيان العام أو المخصص للمسجلين، أو استخدم رمز QR.
                </div>
                <div class="bg-white p-3 rounded-lg border border-amber-100">
                    <span class="font-bold block mb-1 text-amber-600">3. المتابعة</span>
                    تابع عدد الردود والحالة (نشط/غير نشط) من لوحة التحكم هذه.
                </div>
                <div class="bg-white p-3 rounded-lg border border-amber-100">
                    <span class="font-bold block mb-1 text-amber-600">4. تحليل النتائج</span>
                    استعرض النتائج والرسوم البيانية أو قم بتصدير الردود لملف Excel.
                </div>
            </div>
        </div>

        <!-- Header with Stats and Create Button -->
        <div
            class="bg-gradient-to-br from-white to-amber-50 rounded-2xl shadow-md border border-amber-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-amber-100 p-2 rounded-xl ml-3">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">إدارة الاستبيانات</h1>
                    <p class="text-gray-600 text-sm">إنشاء ومتابعة استطلاعات الرأي والبيانات</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="bg-white flex items-center gap-2 rounded-lg shadow-sm border border-gray-200 px-4 py-3 text-center hover:shadow-md transition">
                    <span class="text-xs font-medium text-amber-700">العدد الكلي</span>
                    <span class="text-2xl font-bold text-amber-600">{{ $questionnaires->count() }}</span>
                </div>

                <a href="{{ route('questionnaire.create') }}"
                    class="flex items-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    استبيان جديد
                </a>
            </div>
        </div>

        <!-- Questionnaires Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            @foreach (['#', 'عنوان الاستبيان', 'الهدف', 'الحالة', 'الردود', 'تاريخ الإنشاء', 'الخيارات'] as $header)
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($questionnaires as $questionnaire)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-3 text-sm text-gray-800">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-3">
                                    <div class="font-semibold text-gray-900">{{ $questionnaire->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $questionnaire->questions_count }} سؤال
                                    </div>
                                </td>

                                <td class="px-6 py-3 text-sm">
                                    @if ($questionnaire->target_response == 'open_for_all')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            مفتوح للكل
                                        </span>
                                    @elseif ($questionnaire->target_response == 'registerd_only')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            للمسجلين فقط
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-3 text-sm">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $questionnaire->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $questionnaire->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $questionnaire->is_active ? 'نشط' : 'معطل' }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('questionnaire.answer_index', $questionnaire) }}" class="group">
                                        <span class="bg-amber-100 text-amber-800 text-sm font-semibold px-3 py-1 rounded-full group-hover:bg-amber-200 transition">
                                            {{-- Use correct count logic if eager loaded, fallback to relation count --}}
                                            {{ $questionnaire->answers_count ?? $questionnaire->answers()->count() }}
                                        </span>
                                    </a>
                                </td>

                                <td class="px-6 py-3 text-sm text-gray-500">
                                    {{ $questionnaire->created_at->format('Y-m-d') }}
                                </td>

                                <td class="px-6 py-3 text-sm">
                                    <div class="flex items-center space-x-2 space-x-reverse" x-data="{ open: false }">
                                        
                                        {{-- Edit --}}
                                        <a href="{{ route('questionnaire.edit', $questionnaire) }}"
                                            class="text-green-600 hover:text-green-800 p-1 hover:bg-green-50 rounded transition"
                                            title="تعديل">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>

                                        {{-- Results/Stats --}}
                                        <a href="{{ route('questionnaire.statistics', $questionnaire) }}"
                                            class="text-blue-600 hover:text-blue-800 p-1 hover:bg-blue-50 rounded transition"
                                            title="الإحصائيات">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                        </a>

                                        {{-- Share Link --}}
                                        @if ($questionnaire->is_active)
                                            <a href="{{ route('questionnaire.share_link', $questionnaire) }}"
                                                class="text-purple-600 hover:text-purple-800 p-1 hover:bg-purple-50 rounded transition"
                                                title="رابط المشاركة">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                                            </a>
                                        @endif
                                        
                                        {{-- Structure Edit --}}
                                        <a href="{{ route('questionnaire.question_edit', $questionnaire) }}"
                                           class="text-indigo-600 hover:text-indigo-800 p-1 hover:bg-indigo-50 rounded transition"
                                           title="تعديل الأسئلة">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('questionnaire.delete', $questionnaire) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $questionnaire->title }}', this.form)"
                                                class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition"
                                                title="حذف">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium text-lg">لا توجد استبيانات</p>
                                    <p class="mt-1 text-sm">ابدأ بإضافة استبيانك الأول لقياس رضا المشاركين</p>
                                    <a href="{{ route('questionnaire.create') }}"
                                        class="mt-3 inline-block bg-amber-600 text-white px-5 py-2 rounded-lg hover:bg-amber-700 transition">
                                        ➕ إنشاء استبيان
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination (if using paginate() in controller) --}}
            {{-- @if ($questionnaires->hasPages())
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    {{ $questionnaires->links() }}
                </div>
            @endif --}}
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(title, form) {
                Swal.fire({
                    title: 'هل أدرت حقاً حذف الاستبيان؟',
                    text: `سيتم حذف "${title}" وجميع الردود المرتبطة به.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، حذف نهائي',
                    cancelButtonText: 'تراجع',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }
        </script>
    @endpush
</x-app-layout>
