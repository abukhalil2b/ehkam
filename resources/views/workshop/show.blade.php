<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $workshop->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-100">

                <h3 class="text-2xl font-bold text-blue-700 mb-6 border-b pb-2">
                    البيانات الأساسية للورشة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-gray-700">

                    {{-- 1. Date Range --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> الفترة:</strong>
                        من {{ $workshop->starts_at?->format('Y-m-d') }} إلى {{ $workshop->ends_at?->format('Y-m-d') }}
                    </p>

                    {{-- 2. Place --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> المكان:</strong>
                        {{ $workshop->location ?? 'غير محدد' }}
                    </p>

                    {{-- 3. Written By --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> كتب بواسطة:</strong>
                        <span class="text-blue-600">{{ $workshop->createdBy->name ?? '—' }}</span>
                    </p>

                    {{-- 4. Status --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> الحالة:</strong>
                        <span class="{{ $workshop->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $workshop->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </p>

                </div>

                {{-- Days List --}}
                <div class="mt-8 mb-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-900 text-lg flex items-center">
                            <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            جدول أيام الورشة
                        </h4>
                        <a href="{{ route('workshop.edit', $workshop->id) }}#days-section"
                            class="text-sm bg-white border border-blue-300 text-blue-700 hover:bg-blue-50 px-3 py-1.5 rounded-lg shadow-sm font-medium transition flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            إضافة يوم / تعديل الجدول
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @forelse($workshop->days as $day)
                            <div
                                class="flex flex-col bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-200 min-w-[140px] relative overflow-hidden group">
                                <div
                                    class="absolute top-0 right-0 w-1 h-full {{ $day->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>
                                <span
                                    class="text-xs text-gray-500 mb-1 font-medium">{{ $day->label ?? 'يوم ' . $loop->iteration }}</span>
                                <span class="font-bold text-gray-800 text-lg">{{ $day->day_date->format('Y-m-d') }}</span>
                                <span
                                    class="text-xs mt-1 {{ $day->is_active ? 'text-green-600 font-bold' : 'text-gray-400' }}">
                                    {{ $day->is_active ? 'نشط اليوم' : 'غير نشط' }}
                                </span>
                            </div>
                        @empty
                            <div
                                class="w-full text-center py-4 text-gray-500 bg-white rounded-lg border border-dashed border-gray-300">
                                لا توجد أيام محددة لهذه الورشة بعد.
                                <br>
                                <a href="{{ route('workshop.edit', $workshop->id) }}"
                                    class="text-blue-600 font-bold hover:underline mt-1 inline-block">
                                    اضغط هنا لإضافة أيام
                                </a>
                            </div>
                        @endforelse
                    </div>
                    <p class="text-xs text-gray-500 mt-3">
                        <span class="font-bold text-gray-700">ملاحظة:</span> لإضافة "اليوم الثاني" أو أيام إضافية، اضغط
                        على زر "إضافة يوم / تعديل الجدول" أعلاه.
                    </p>
                </div>

                <hr class="my-8 border-gray-200">

                {{-- In your show.blade.php --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">قائمة الحضور</h3>
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">#</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">اسم الحاضر</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">المسمى الوظيفي
                                    </th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">القسم</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($workshop->attendances as $attendance)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $attendance->attendee_name }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->job_title ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->department ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                            لا يوجد حضور مسجل
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t flex justify-end">
                    <a href="{{ route('workshop.edit', $workshop->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        تعديل الورشة
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>