<x-app-layout>
    <x-slot name="header">
        تفاصيل النشاط
    </x-slot>

    <div class="container py-8 mx-auto px-4">

        <h2 class="text-3xl font-bold mb-2">{{ $activity->title }}</h2>

        <p class="text-gray-600 mb-2">
            المشروع: {{ $activity->project->title ?? '—' }}
        </p>

        <p class="text-sm text-gray-500 mb-6">
            <span class="font-semibold">سنة التقييم:</span> {{ $currentYear }}
        </p>

        {{-- Workflow Status --}}
        @if($activity->isInWorkflow())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">الحالة:</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $activity->status === 'completed' ? 'bg-green-100 text-green-800' :
            ($activity->status === 'rejected' ? 'bg-red-100 text-red-800' :
                ($activity->status === 'returned' ? 'bg-yellow-100 text-yellow-800' :
                    ($activity->status === 'delayed' ? 'bg-orange-100 text-orange-800' :
                        'bg-blue-100 text-blue-800'))) }}">
                            {{ $activity->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">المرحلة الحالية:</p>
                        <p class="text-gray-800">{{ $activity->currentStage->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">الفريق المسؤول:</p>
                        <p class="text-gray-800">{{ $activity->currentStage->team->name ?? '—' }}</p>
                    </div>
                </div>
                @if($activity->isDelayed())
                    <div class="mt-3 p-2 bg-orange-100 border border-orange-300 rounded">
                        <p class="text-sm text-orange-900">
                            <span class="font-semibold">⚠️ تحذير:</span> {{ $activity->escalation_status }}
                        </p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">الحالة:</span>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                        {{ $activity->status_label }}
                    </span>
                </p>
            </div>
        @endif

        {{-- Steps List --}}
        @if($activity->steps->isNotEmpty())
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">خطوات النشاط</h3>
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    {{ __('الخطوة') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    {{ __('الوزن') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    {{ __('المرفقات') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($activity->steps as $step)
                                <tr>
                                    <td class="px-6 py-4">{{ $step->name }}</td>
                                    <td class="px-6 py-4">{{ $step->weight }}</td>
                                    <td class="px-6 py-4">
                                        @if($step->supporting_document)
                                            <a href="{{ asset('storage/' . $step->supporting_document) }}" target="_blank"
                                                class="text-indigo-600 hover:underline">عرض المرفق</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Workflow Actions (Visible only to authorized users) --}}
        @if(auth()->check() && auth()->user()->canActOnActivity($activity))
            <div class="bg-white border border-indigo-200 rounded-lg p-6 mb-8 shadow-md" x-data="{ action: null }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-indigo-900">إجراءات سير العمل</h3>
                    <span class="text-sm text-gray-500">لديك صلاحية اتخاذ إجراء في هذه المرحلة</span>
                </div>

                <div class="flex gap-4">
                    {{-- Approve Button --}}
                    <form action="{{ route('workflow.approve', $activity) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('هل أنت متأكد من الموافقة وتمرير النشاط؟')"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition">
                            موافقة وتمرير
                        </button>
                    </form>

                    {{-- Return Button (Opens Modal) --}}
                    <button @click="action = 'return'" type="button"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded shadow transition">
                        إعادة للمرحلة السابقة
                    </button>

                    {{-- Reject Button (Opens Modal) --}}
                    <button @click="action = 'reject'" type="button"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition">
                        رفض نهائي
                    </button>
                </div>

                {{-- Return Modal --}}
                <div x-show="action === 'return'" x-transition class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="action = null">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <div
                            class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('workflow.return', $activity) }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">إعادة النشاط للمراجعة</h3>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">ملاحظات عامة:</label>
                                        <textarea name="comments" rows="3"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                            placeholder="سبب الإعادة..."></textarea>
                                    </div>

                                    @if($activity->steps->isNotEmpty())
                                        <div class="mb-4">
                                            <h4 class="font-bold text-gray-700 mb-2 text-sm border-b pb-1">ملاحظات على الخطوات
                                                (اختياري):</h4>
                                            <div class="space-y-3 max-h-60 overflow-y-auto p-1">
                                                @foreach($activity->steps as $step)
                                                    <div>
                                                        <label
                                                            class="block text-xs font-semibold text-gray-600">{{ $step->name }}</label>
                                                        <input type="text" name="step_feedbacks[{{ $step->id }}]"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="ملاحظات لهذه الخطوة...">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        تأكيد الإعادة
                                    </button>
                                    <button type="button" @click="action = null"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div x-show="action === 'reject'" x-transition class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="action = null">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <div
                            class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('workflow.reject', $activity) }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-red-900 mb-4">رفض النشاط نهائياً</h3>
                                    <p class="text-sm text-gray-500 mb-4">سيتم إيقاف سير العمل لهذا النشاط وتغيير حالته إلى
                                        "مرفوض". هذا الإجراء لا يمكن التراجع عنه.</p>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">سبب الرفض
                                            (إجباري):</label>
                                        <textarea name="comments" rows="3" required
                                            class="shadow-sm focus:ring-red-500 focus:border-red-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                            placeholder="اذكر سبب الرفض..."></textarea>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        تأكيد الرفض
                                    </button>
                                    <button type="button" @click="action = null"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mb-8 flex flex-wrap gap-3">
            <a href="{{ route('activity.index', ['year' => $currentYear]) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                الأنشطة
            </a>

            @if ($canSubmitNew)
                <a href="{{ route('assessment_result.create', $activity->id) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    تقييم جديد
                </a>
            @elseif ($canUpdate)
                <a href="{{ route('assessment_result.edit', $activity->id) }}"
                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    تعديل التقييم الحالي
                </a>
            @endif

            {{-- Submit to Workflow (Visible to Creator if Draft) --}}
            @if($activity->status === 'draft' && auth()->id() == $activity->creator_id)
                <form action="{{ route('workflow.submit', $activity) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('هل أنت متأكد من إرسال النشاط للاعتماد؟')"
                        class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded shadow">
                        إرسال للاعتماد
                    </button>
                </form>
            @endif
        </div>

        <!-- Summary -->
        <h3 class="text-2xl font-semibold mt-8 mb-4 border-b pb-2">
            ملخص التقييم الخاص بك
        </h3>

        @if ($userSummary['results']->isEmpty())
            <p class="text-gray-500">
                لم تقم بتقديم أي تقييم لهذا النشاط بعد.
            </p>
        @else
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">
                                المُقيم
                            </th>

                            @if ($hasRangeResults)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">
                                    النقاط
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">
                                    النسبة
                                </th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="px-6 py-4 font-medium">
                                {{ $userSummary['user_name'] }}
                            </td>

                            @if ($hasRangeResults)
                                            <td class="px-6 py-4 text-center font-bold text-blue-600">
                                                {{ $userSummary['total_score'] }} / {{ $userSummary['max_score'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                                                                    {{ $userSummary['percentage'] >= 75 ? 'bg-green-100 text-green-800'
                                : ($userSummary['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-red-100 text-red-800') }}">
                                                    %{{ $userSummary['percentage'] }}
                                                </span>
                                            </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Detailed Results -->
        <h3 class="text-2xl font-semibold mt-10 mb-4 border-b pb-2">
            النتائج التفصيلية ({{ $allQuestions->count() }} سؤال)
        </h3>

        <div class="space-y-6">
            @foreach ($allQuestions as $question)
                @php
                    $result = $userSummary['results']->get($question->id);
                @endphp

                <div class="p-5 border rounded bg-gray-50">
                    <p class="font-bold text-lg mb-2">
                        {{ $loop->iteration }}. {{ $question->content }}
                        <span class="text-sm text-gray-500">
                            ({{ $question->type === 'range' ? 'نقاط' : 'نص' }})
                        </span>
                    </p>

                    @if ($result)
                        @if ($question->type === 'range')
                            <p class="text-blue-700">
                                النقاط:
                                <strong>{{ $result->range_answer }}</strong>
                                / {{ $question->max_point }}
                            </p>
                        @else
                            <div class="bg-white p-3 rounded text-sm">
                                {!! nl2br(e($result->text_answer)) !!}
                            </div>
                        @endif

                        @if ($result->note)
                            <p class="mt-2 text-sm italic text-gray-600">
                                ملحوظة: {{ $result->note }}
                            </p>
                        @endif

                        <p class="text-xs text-gray-500 mt-2">
                            آخر تحديث: {{ $result->updated_at->format('Y-m-d H:i') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">
                            لم يتم تسجيل إجابة لهذا السؤال.
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>