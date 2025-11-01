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

                    {{-- 1. Date --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> التاريخ:</strong>
                        {{ \Carbon\Carbon::parse($workshop->date)->isoFormat('dddd، D MMMM YYYY') }}
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

                    {{-- 4. Creation Date (Optional but useful) --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> تاريخ التسجيل:</strong>
                        {{ \Carbon\Carbon::parse($workshop->created_at)->diffForHumans() }}
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
