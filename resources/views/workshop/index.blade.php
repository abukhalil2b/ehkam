<x-app-layout>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <!-- Header with Stats and Create Button -->
       <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg border border-blue-100 p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <!-- Text Content -->
        <div class="flex-1 mb-6 md:mb-0">
            <div class="flex items-center mb-3">
                <div class="bg-blue-100 p-2 rounded-lg ml-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">إدارة ورش العمل</h1>
                    <p class="text-gray-600 mt-1">إدارة وتنظيم جميع ورش العمل والحضور</p>
                </div>
            </div>
        </div>

        <!-- Stats and Actions -->
        <div class="flex items-center space-x-4 space-x-reverse">
            <!-- Total Count -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $workshops->total() }}</div>
                <div class="text-xs font-medium text-blue-700">إجمالي الورش</div>
            </div>

            <!-- Create Button -->
            <a href="{{ route('workshop.create') }}" 
               class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center group">
                <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة ورشة جديدة
            </a>
        </div>
    </div>
</div>

        <!-- Workshops Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-2l4 4m6 0v12m0 0l4-2m-2 4l-2-2"></path>
                                    </svg>
                                    #
                                </div>
                            </th>
                            <th class="px-6 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    العنوان
                                </div>
                            </th>
                            <th class="px-6 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    التاريخ والوقت
                                </div>
                            </th>
                            <th class="px-6 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                   بواسطة
                                </div>
                            </th>
                            <th class="px-6 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-2 0 2 2 0 014 0zM7 10a2 2 0 11-2 0 2 2 0 014 0z"></path>
                                    </svg>
                                    عدد الحضور
                                </div>
                            </th>
                            <th class="px-6 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    الخيارات
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($workshops as $workshop)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-2 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $loop->iteration + (($workshops->currentPage() - 1) * $workshops->perPage()) }}</div>
                                </td>
                                <td class="px-6 py-2">
                                    <div class="text-sm font-semibold text-gray-900">{{ $workshop->title }}</div>
                                    @if($workshop->location)
                                        <div class="text-sm text-gray-500 flex items-center mt-1">
                                            <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-2.244-2.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $workshop->location }}
                                        </div>
                                    @endif
                                    @if($workshop->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            نشط
                                        </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            مغلق
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $workshop->starts_at->format('Y-m-d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $workshop->starts_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full p-2 ml-3">
                                            <svg class="w-2 h-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $workshop->createdBy->name ?? '-' }}</div>
                                            <div class="text-sm text-gray-500">{{ $workshop->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full flex items-center">
                                            <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-2 0 2 2 0 014 0zM7 10a2 2 0 11-2 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ $workshop->attendances->count() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <a href="{{ route('workshop.show', $workshop) }}" 
                                           class="text-blue-600 hover:text-blue-900 flex items-center transition duration-150"
                                           title="عرض التفاصيل">
                                            <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-2.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            عرض
                                        </a>
                                        <a href="{{ route('workshop.edit', $workshop) }}" 
                                           class="text-green-600 hover:text-green-900 flex items-center transition duration-150"
                                           title="تعديل الورشة">
                                            <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            تعديل
                                        </a>
                                        <form action="{{ route('workshop.destroy', $workshop) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $workshop->title }}', this.form)" 
                                                    class="text-red-600 hover:text-red-900 flex items-center transition duration-150"
                                                    title="حذف الورشة">
                                                <svg class="w-2 h-2 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">لا توجد ورش عمل</p>
                                        <p class="mt-2">ابدأ بإضافة ورشة العمل الأولى</p>
                                        <a href="{{ route('workshop.create') }}" class="mt-2 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                                            إضافة ورشة جديدة
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($workshops->hasPages())
                <div class="bg-gray-50 px-6 py-2 border-t border-gray-200">
                    {{ $workshops->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmDelete(workshopTitle, form) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم حذف ورشة العمل "${workshopTitle}" بشكل دائم!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>