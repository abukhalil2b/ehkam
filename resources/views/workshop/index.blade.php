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
                    استعرض بيانات الورشة وستجد رابط تسجيل الحضور
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
                    class="bg-white flex items-center gap-2 rounded-lg shadow-sm border border-gray-200 px-4 py-3 text-center hover:shadow-md transition">
                    <span class="text-xs font-medium text-blue-700">إجمالي الورش</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $workshops->total() }}</span>
                </div>


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
                                    <div class="flex items-center space-x-2 space-x-reverse justify-end">
                                        {{-- Show --}}
                                        <a href="{{ route('workshop.show', $workshop) }}"
                                            class="text-blue-600 hover:text-blue-800 p-1 hover:bg-blue-50 rounded transition"
                                            title="عرض التفاصيل">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('workshop.edit', $workshop) }}"
                                            class="text-green-600 hover:text-green-800 p-1 hover:bg-green-50 rounded transition"
                                            title="تعديل">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Report Button -->
                                        <a href="{{ route('workshop.attendance_report', ['workshop_id' => $workshop->id]) }}"
                                            class="text-teal-600 hover:text-teal-900 p-2 hover:bg-teal-50 rounded-lg transition-colors group relative"
                                            title="تقرير الحضور">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </a>

                                        {{-- Edit Status --}}
                                        <a href="{{ route('workshop.edit_status', $workshop) }}"
                                            class="text-amber-600 hover:text-amber-800 p-1 hover:bg-amber-50 rounded transition"
                                            title="تغيير الحالة">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </a>

                                        {{-- Replicate --}}
                                        <form action="{{ route('workshop.replicate', $workshop) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="button"
                                                onclick="confirmReplicate('{{ $workshop->title }}', this.form)"
                                                class="text-indigo-600 hover:text-indigo-800 p-1 hover:bg-indigo-50 rounded transition"
                                                title="إنشاء نسخة">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form action="{{ route('workshop.destroy', $workshop) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $workshop->title }}', this.form)"
                                                class="text-red-600 hover:text-red-800 p-1 hover:bg-red-50 rounded transition"
                                                title="حذف">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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