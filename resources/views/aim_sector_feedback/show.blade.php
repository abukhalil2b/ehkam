<x-sect-layout>
    <div class="mb-6 flex justify-between items-center bg-white p-4 rounded shadow">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">تفاصيل التغذية الراجعة</h1>
            <p class="text-gray-500">مراجعة البيانات ومسار العمل</p>
        </div>
        <a href="{{ route('aim_sector_feedback.index', ['aim' => $feedback->aim_id]) }}"
            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
            عودة للقائمة
        </a>
    </div>

    @include('aim_sector_feedback._show_aim')

    {{-- Workflow Explanation Box --}}
    <div class="max-w-2xl mx-auto mb-6">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        آلية عمل النظام واستعراض حالة الطلب:
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 space-y-2">
                        <p>
                            1. <strong>الإرسال:</strong> بمجرد قيام المستخدم بحفظ البيانات، يتم إرسال الطلب وتتغير حالته
                            تلقائياً إلى <span class="font-bold">"بانتظار الموافقة"</span>.
                        </p>
                        <p>
                            2. <strong>الاعتماد:</strong> يقوم المشرف بمراجعة البيانات المدخلة؛ فإذا كانت مكتملة وصحيحة،
                            يضغط على <span class="font-bold">"اعتماد"</span> للموافقة النهائية.
                        </p>
                        <p>
                            3. <strong>الإعادة:</strong> في حال وجود ملاحظات أو نواقص، يقوم المشرف بالضغط على زر <span
                                class="font-bold">"إعادة"</span> مع تدوين الملاحظات اللازمة للمستخدم كي يقوم بالتعديل.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow Section --}}
    <div class="max-w-2xl mx-auto mt-6">
        <div class="bg-white shadow-xl rounded-xl overflow-hidden p-6 border-t-4 border-blue-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">مسار العمل (Workflow)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <strong class="text-sm text-gray-500 block">الحالة الحالية:</strong>
                    <span class="text-lg font-bold 
                        {{ $feedback->status == 'completed' ? 'text-green-600' : '' }}
                        {{ $feedback->status == 'rejected' ? 'text-red-600' : '' }}
                        {{ $feedback->status == 'in_progress' ? 'text-blue-600' : 'text-gray-700' }}
                    ">
                        {{ $feedback->status_label }}
                    </span>
                </div>
                <div>
                    <strong class="text-sm text-gray-500 block">المرحلة الحالية:</strong>
                    @if($feedback->currentStage)
                        <span class="text-lg font-bold text-indigo-700">
                            {{ $feedback->currentStage->name ?? '—' }}
                        </span>
                    @else
                        <span class="text-gray-400 italic">لا توجد مرحلة نشطة</span>
                    @endif
                </div>
            </div>

            {{-- Submission Action for Drafts --}}
            @if($feedback->status == 'draft' || $feedback->status == 'returned')
                <div class="bg-gray-50 p-4 border-t border-gray-200 mt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-gray-700">هل البيانات مكتملة؟</h4>
                            <p class="text-xs text-gray-500">عند الانتهاء من التعديل، قم بإرسال الطلب للاعتماد.</p>
                        </div>
                        <form action="{{ route('aim_sector_feedback.submit', $feedback) }}" method="POST">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('هل أنت متأكد من رغبتك في إرسال البيانات للاعتماد؟ لن تتمكن من التعديل بعد الإرسال.')"
                                class="px-6 py-2 bg-indigo-600 text-white font-bold rounded hover:bg-indigo-700 transition shadow-md flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                إرسال للاعتماد
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Workflow Actions (Placeholder logic, adapt to actual permissions/service) --}}
            @if($feedback->isInWorkflow() && !$feedback->isTerminal())
                <div class="mt-6 pt-4 border-t">
                    <h4 class="font-semibold text-gray-700 mb-2">إجراءات المسار:</h4>
                    <div class="flex gap-2">
                        {{-- You would typically check permissions here --}}
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            موافقة / انتقال للتالي
                        </button>
                        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                            إعادة / رفض
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">* هذه الأزرار للعرض، يجب ربطها بخدمة WorkflowService.</p>
                </div>
            @endif
        </div>
    </div>
    {{-- Extra Spacing --}}
    <div class="mb-12"></div>
</x-sect-layout>