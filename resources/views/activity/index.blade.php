<x-app-layout>
    <x-slot name="header">
        الأنشطة
    </x-slot>

    <div class="container py-8 mx-auto px-4">
        <div class="flex items-baseline justify-between mb-8 pb-3 border-b border-gray-200">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                قائمة الأنشطة
            </h2>

            <span
                class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800 shadow-sm">
                إجمالي: {{ count($activities) }}
            </span>
        </div>
        <a href="{{ route('assessment_questions.index') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            أسئلة الأنشطة
        </a>
        <a href="{{ route('activity.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            إضافة نشاط جديد
        </a>
        <a href="{{ route('assessment.report') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            تقرير
        </a>

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            العنوان</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشروع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($activities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->project->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('activity.show', $activity) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 inline-block w-44">عرض
                                    التقييم</a>
                                @php
                                    // Check if the current activity's ID exists in the array of submitted IDs
                                    $userSubmitted = in_array($activity->id, $submittedActivityIds);
                                @endphp

                                <div class="mt-4">
                                    @if ($userSubmitted)
                                        <a href="{{ route('assessment_result.edit', $activity->id) }}"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 inline-block w-44">
                                            تعديل تقييمي الحالي
                                        </a>
                                    @else
                                        <a href="{{ route('assessment_result.create', $activity->id) }}"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 inline-block w-44">
                                            + تقييم جديد
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
