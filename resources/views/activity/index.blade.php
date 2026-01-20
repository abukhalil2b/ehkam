<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">
                قائمة الأنشطة {{ $selectedYear }}
            </h2>

            <div class="w-44 px-3 py-2 text-xs font-semibold rounded-xl
                        bg-purple-100 text-purple-800 shadow-sm space-y-1">
                <div>إجمالي: {{ count($activities) }}</div>
                <div>تم تقييم: {{ count($submittedActivityIds) }}</div>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-4 py-8">

        {{-- Years Tabs --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach ($availableYears as $year)
                    <a href="{{ route('activity.index', ['year' => $year]) }}" class="px-5 py-2 text-sm font-semibold rounded-full transition
                                   {{ $year == $selectedYear
                ? 'bg-indigo-600 text-white shadow ring-2 ring-indigo-300'
                : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-100' }}">
                        {{ $year }}
                    </a>
            @endforeach
        </div>

        {{-- Top Actions --}}
        <div class="flex gap-3 mb-6">
            <a href="{{ route('assessment_questions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold
                      text-white bg-indigo-600 rounded-lg shadow
                      hover:bg-indigo-700 transition">
                أسئلة الأنشطة
            </a>

            <a href="{{ route('project_assessment_report') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold
                      text-indigo-700 bg-indigo-100 rounded-lg
                      hover:bg-indigo-200 transition">
                تقرير
            </a>
        </div>

        {{-- Activities Table --}}
        <div class="shadow border border-gray-200 sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase">
                            النشاط
                        </th>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase">
                            المشروع
                        </th>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase">
                            الحالة
                        </th>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase">
                            الإجراءات
                        </th>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase">
                            التقييم بواسطة
                        </th>
                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase">
                            التاريخ
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($activities as $activity)
                                    @php
                                        $userSubmitted = in_array($activity->id, $submittedActivityIds);
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $activity->title }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $activity->project->title ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $activity->status === 'completed' ? 'bg-green-100 text-green-800' :
                        ($activity->status === 'rejected' ? 'bg-red-100 text-red-800' :
                            ($activity->status === 'returned' ? 'bg-yellow-100 text-yellow-800' :
                                ($activity->status === 'delayed' ? 'bg-orange-100 text-orange-800' :
                                    'bg-gray-100 text-gray-800'))) }}">
                                                {{ $activity->status_label }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap space-y-3">
                                            <a href="{{ route('activity.show', $activity) }}" class="inline-flex justify-center w-44 px-4 py-2
                                                              text-white bg-slate-700 rounded-lg
                                                              hover:bg-slate-800 transition shadow">
                                                عرض التقييم
                                            </a>

                                            @if ($userSubmitted)
                                                <a href="{{ route('assessment_result.edit', $activity->id) }}" class="inline-flex justify-center w-44 px-4 py-2
                                                                          text-white bg-amber-500 rounded-lg
                                                                          hover:bg-amber-600 transition shadow">
                                                    تعديل تقييمي الحالي
                                                </a>
                                            @else
                                                <a href="{{ route('assessment_result.create', $activity->id) }}" class="inline-flex justify-center w-44 px-4 py-2
                                                                          text-white bg-emerald-600 rounded-lg
                                                                          hover:bg-emerald-700 transition shadow">
                                                    + تقييم جديد
                                                </a>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $activity->assessmentResults->first()?->user->name ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $activity->created_at->format('d-m-Y') }}
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>