<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Help / Info Section --}}
        <div class="bg-blue-50 rounded-xl border border-blue-100 p-4 mb-6 shadow-sm">
            <h3 class="font-bold text-blue-800 text-lg mb-2 flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                كيفية استخدام نظام الورش متعددة الأيام
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-blue-900">
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <span class="font-bold block mb-1 text-blue-600">1. إنشاء الورشة</span>
                    قم بإنشاء ورشة جديدة وحدد "الأيام" (مثال: اليوم الأول، اليوم الثاني) أثناء الإنشاء أو التعديل.
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <span class="font-bold block mb-1 text-blue-600">2. تسجيل الحضور</span>
                    استخدم <a href="{{ route('workshow_attendance_register') }}" class="text-blue-700 font-bold underline hover:text-blue-900">رابط تسجيل الحضور</a>. النظام سيكتشف تلقائياً "اليوم الحالي" بناءً على التاريخ لتسجيل الحضور
                    لليوم الصحيح.
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <span class="font-bold block mb-1 text-blue-600">3. إضافة أيام لاحقاً</span>
                    في أي وقت، يمكنك الدخول لصفحة "تعديل" الورشة وإضافة أيام جديدة (زر "بناء الجدول").
                </div>
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <span class="font-bold block mb-1 text-blue-600">4. التقارير</span>
                    صفحة "تقرير الحضور" تعرض جدولاً تفصيلياً يوضح حضور كل مشترك في كل يوم من أيام الورشة بشكل منفصل.
                </div>
            </div>
        </div>

        <!-- Header with Stats and Create Button -->
        <div
            class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-md border border-blue-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-blue-100 p-2 rounded-xl ml-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">إدارة ورش العمل</h1>
                    <p class="text-gray-600 text-sm">إدارة وتنظيم جميع ورش العمل والحضور</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 space-x-reverse">
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3 text-center hover:shadow-md transition">
                    <div class="text-2xl font-bold text-blue-600">{{ $workshops->total() }}</div>
                    <div class="text-xs font-medium text-blue-700">إجمالي الورش</div>
                </div>

                <a href="{{ route('workshow_attendance_register') }}"
                    class="bg-white text-purple-700 hover:bg-purple-50 border border-purple-300 px-4 py-3 rounded-lg font-semibold shadow-sm hover:shadow transition mr-2 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    تسجيل الحضور
                </a>

                <a href="{{ route('workshop.attendance_report') }}"
                    class="bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 px-4 py-3 rounded-lg font-semibold shadow-sm hover:shadow transition mr-2 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                    تقرير الحضور
                </a>

                <a href="{{ route('workshop.create') }}"
                    class="flex items-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    إضافة ورشة جديدة
                </a>
            </div>
        </div>

        <!-- Workshops Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            @foreach (['#', 'العنوان', 'التاريخ والوقت', 'بواسطة', 'عدد الحضور', 'الخيارات'] as $header)
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($workshops as $workshop)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-3 text-sm text-gray-800">
                                    {{ $loop->iteration + ($workshops->currentPage() - 1) * $workshops->perPage() }}
                                </td>

                                <td class="px-6 py-3">
                                    <div class="font-semibold text-gray-900">{{ $workshop->title }}</div>
                                    @if ($workshop->location)
                                        <div class="text-sm text-gray-500 mt-1">{{ $workshop->location }}</div>
                                    @endif
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-2
                                                    {{ $workshop->is_active ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $workshop->is_active ? 'مفعلة' : 'مغلقة' }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-sm">
                                    <div class="text-gray-800">{{ $workshop->starts_at->format('Y-m-d') }}</div>
                                    <div class="text-gray-500">{{ $workshop->starts_at->format('H:i') }}</div>
                                </td>

                                <td class="px-6 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $workshop->createdBy->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $workshop->created_at->diffForHumans() }}</div>
                                </td>

                                <td class="px-6 py-3 text-center">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                        {{ $workshop->attendances->count() }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-sm">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <a href="{{ route('workshop.show', $workshop) }}"
                                            class="text-blue-600 hover:text-blue-900 font-medium transition" title="عرض">
                                            👁 عرض
                                        </a>

                                        <a href="{{ route('workshop.edit', $workshop) }}"
                                            class="text-green-600 hover:text-green-800 font-medium transition"
                                            title="تعديل">
                                            ✏️ تعديل
                                        </a>

                                        <a href="{{ route('workshop.edit_status', $workshop) }}"
                                            class="text-yellow-600 hover:text-yellow-800 font-medium transition"
                                            title="تفعيل/تعطيل">
                                            🔄 الحالة
                                        </a>

                                        <form action="{{ route('workshop.replicate', $workshop) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="button"
                                                onclick="confirmReplicate('{{ $workshop->title }}', this.form)"
                                                class="text-indigo-600 hover:text-indigo-900 font-medium transition"
                                                title="إنشاء نسخة">
                                                📄 نسخة
                                            </button>
                                        </form>

                                        <form action="{{ route('workshop.destroy', $workshop) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $workshop->title }}', this.form)"
                                                class="text-red-600 hover:text-red-900 font-medium transition" title="حذف">
                                                🗑 حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium text-lg">لا توجد ورش عمل</p>
                                    <p class="mt-1 text-sm">ابدأ بإضافة ورشة العمل الأولى</p>
                                    <a href="{{ route('workshop.create') }}"
                                        class="mt-3 inline-block bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                                        ➕ إضافة ورشة جديدة
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($workshops->hasPages())
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    {{ $workshops->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(title, form) {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف "${title}" بشكل دائم!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }

            function confirmReplicate(title, form) {
                Swal.fire({
                    title: 'إنشاء نسخة؟',
                    text: `هل ترغب بإنشاء نسخة من "${title}"؟`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'نعم، أنشئ نسخة',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }
        </script>
    @endpush
</x-app-layout>