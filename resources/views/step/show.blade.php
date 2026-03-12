<x-app-layout title="تفاصيل الخطوة">

    <div class="py-6" dir="rtl">
        <div class="max-w-5xl mx-auto px-4">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
                <h1 class="text-2xl font-bold text-[#1b5e20]">
                    {{ $step->name }}
                </h1>

                <a href="{{ route('step.index', $step->project_id) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow">
                    ⟵ رجوع إلى الخطوات
                </a>
            </div>

            {{-- Tabs --}}
            <div x-data="{ activeTab: 'info' }" class="space-y-4">

                <div class="flex gap-4 border-b border-gray-200 flex-wrap">
                    <button type="button" @click="activeTab='info'" :class="activeTab === 'info' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'" class="pb-2">معلومات الخطوة</button>
                    <button type="button" @click="activeTab='units'" :class="activeTab === 'units' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'" class="pb-2">المنفذون</button>
                    <button type="button" @click="activeTab='targets'" :class="activeTab === 'targets' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'" class="pb-2">توزيع المستهدف</button>
                    <button type="button" @click="activeTab='evidence'" :class="activeTab === 'evidence' ? 'border-b-2 border-blue-600 text-blue-700 font-semibold' :
                            'text-gray-500'" class="pb-2 flex items-center gap-1">
                        📎 الأدلة الداعمة
                        @if($step->evidenceFiles->where('status','pending')->count() > 0)
                            <span class="bg-yellow-400 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                {{ $step->evidenceFiles->where('status','pending')->count() }}
                            </span>
                        @endif
                    </button>
                </div>

                {{-- Info Tab --}}
                <div x-show="activeTab==='info'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
                        <div>
                            <p class="font-semibold text-[#1b5e20]">تاريخ البداية:</p>
                            <p>{{ $step->start_date ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">تاريخ النهاية:</p>
                            <p>{{ $step->end_date ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">النسبة المستهدفة:</p>
                            <p>{{ $step->target_percentage ?? 0 }}%</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">المرحلة:</p>
                            <p>{{ $phases[$step->phase]['title'] ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">الحالة:</p>
                            @include('step.partials.step-status')
                        </div>
                    </div>
                </div>

                {{-- Organizational Units Tab --}}
                <div x-show="activeTab==='units'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-2">

                    @if ($step->StepOrgUnitTasks && $step->StepOrgUnitTasks->count())

                        <section>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-right">
                                أداء المنفذون
                            </h2>

                            <div class="text-xl text-red-800 font-bold mb-4">
                                المستهدف الكلي للمؤشر: {{ number_format($overallTarget) }}
                            </div>

                            <table class="min-w-full border text-right border-gray-300 text-sm" dir="rtl">
                                <thead>
                                    <tr>
                                        <th class="bg-[#1e3d4f] text-white p-3 border">المنفذون</th>

                                        @php
                                            // Get unique period template names from first unit
                                            $periodNames = collect($unitData)
                                                ->flatMap(fn($u) => array_keys($u['periods']))
                                                ->unique();
                                        @endphp

                                        @foreach ($periodNames as $periodName)
                                            <th class="bg-[#1e3d4f] text-white p-3 border">
                                                {{ $periodName }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unitData as $unit)
                                        @php
                                            $totalPercentage =
                                                $unit['total_target'] > 0
                                                ? round(($unit['total_achieved'] / $unit['total_target']) * 100, 2)
                                                : 0;
                                        @endphp

                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">
                                                <div>{{ $unit['name'] }}</div>
                                                <div>المستهدف الكلي للفترات: {{ number_format($unit['total_target']) }}
                                                </div>
                                                <div>المحقق: {{ number_format($unit['total_achieved']) }}</div>
                                                <div>بنسبة {{ $totalPercentage }} %</div>
                                            </td>

                                            @foreach ($periodNames as $periodName)
                                                @php
                                                    $period = $unit['periods'][$periodName] ?? null;
                                                @endphp
                                                <td class="p-3 border align-top">
                                                    @if ($period)
                                                        <div>المستهدف: {{ number_format($period['target']) }}</div>
                                                        <div>المحقق: {{ number_format($period['achieved']) }}</div>
                                                        <div>بنسبة {{ $period['percentage'] }} %</div>
                                                    @else
                                                        <div class="text-gray-400">—</div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </section>
                    @else
                        <p class="text-gray-500">لا يوجد منفذون.</p>
                    @endif


                </div>

                {{-- Supporting Files Tab REMOVED — merged into الأدلة الداعمة tab below --}}

                {{-- Targets Tab --}}
                <div x-show="activeTab==='targets'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-4">
                    @if ($step->is_need_to_put_target && $step->StepOrgUnitTasks->count())
                        @foreach ($step->StepOrgUnitTasks as $task)
                            <div class="mb-4">
                                <p class="font-semibold text-[#1b5e20] mb-2">{{ $task->organizational_name }}:</p>
                                @if ($task->StepOrgUnitTaskTargets && $task->StepOrgUnitTaskTargets->count())
                                    <ul class="list-disc pr-4 space-y-1 text-gray-700">
                                        @foreach ($task->StepOrgUnitTaskTargets as $target)
                                            <li>{{ $target->periodTemplate->name ?? 'فترة غير مسماة' }} :
                                                {{ $target->target }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">—</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">لا توجد أهداف تم إدخالها لهذا الخطوة.</p>
                    @endif
                </div>

                {{-- ========== EVIDENCE FILES TAB ========== --}}
                <div x-show="activeTab==='evidence'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-6" dir="rtl">

                    <h3 class="text-base font-bold text-gray-700 flex items-center gap-2">
                        📎 الأدلة الداعمة
                        <span class="text-xs text-gray-400 font-normal">— الوثائق المرجعية وملفات الإثبات بعد تنفيذ الخطوة</span>
                    </h3>

                    {{-- Supporting document reference text --}}
                    @if($step->supporting_document)
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">📄 وثيقة مرجعية للخطوة</p>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $step->supporting_document }}</p>
                    </div>
                    @endif

                    @if($step->supporting_document)
                    <hr class="border-gray-100">
                    @endif

                    {{-- UPLOAD FORM — only visible to User 3 (evidence.upload) --}}
                    @can('evidence.upload')
                        @if($step->project->status === 'approved')
                            <div class="border-2 border-dashed border-blue-200 rounded-xl p-5 bg-blue-50">
                                <p class="text-sm font-semibold text-blue-700 mb-3">📤 رفع ملف إثبات جديد</p>
                                <form action="{{ route('evidence.store', $step->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                                        <input type="file" name="evidence_file" required
                                            class="flex-1 text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:font-semibold hover:file:bg-blue-700 cursor-pointer">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg transition shadow-sm text-sm">
                                            رفع الملف
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">الصيغ المقبولة: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX — الحد الأقصى: 20 MB</p>
                                    @error('evidence_file')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </form>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                                ⚠️ رفع الملفات متاح فقط بعد اعتماد المشروع.
                            </div>
                        @endif
                    @endcan

                    {{-- FILES LIST --}}
                    @if($step->evidenceFiles->count() > 0)
                        <div class="space-y-3">
                            @foreach($step->evidenceFiles->sortByDesc('created_at') as $ef)
                                @php
                                    $statusBg = match($ef->status) {
                                        'approved' => 'bg-green-50 border-green-200',
                                        'returned' => 'bg-red-50 border-red-200',
                                        default    => 'bg-yellow-50 border-yellow-200',
                                    };
                                    $statusColor = match($ef->status) {
                                        'approved' => 'text-green-700 bg-green-100',
                                        'returned' => 'text-red-700 bg-red-100',
                                        default    => 'text-yellow-700 bg-yellow-100',
                                    };
                                @endphp
                                <div class="border rounded-xl p-4 {{ $statusBg }}">
                                    <div class="flex flex-col sm:flex-row justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-semibold text-gray-800 text-sm truncate">{{ $ef->file_name }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $statusColor }}">{{ $ef->statusLabel }}</span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500 flex flex-wrap gap-3">
                                                <span>رُفع بواسطة: {{ $ef->uploader->name ?? '—' }}</span>
                                                <span>{{ $ef->created_at->format('Y-m-d H:i') }}</span>
                                                @if($ef->reviewed_by)
                                                    <span>راجعه: {{ $ef->reviewer->name ?? '—' }} في {{ $ef->reviewed_at?->format('Y-m-d') }}</span>
                                                @endif
                                            </div>
                                            @if($ef->reviewer_notes)
                                                <div class="mt-2 bg-white rounded-lg px-3 py-2 text-xs text-gray-700 border border-gray-200">
                                                    💬 <span class="font-semibold">ملاحظة المراجع:</span> {{ $ef->reviewer_notes }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col gap-2 items-start sm:items-end shrink-0">
                                            <a href="{{ route('evidence.download', $ef->id) }}"
                                                class="text-xs bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-1.5 rounded-lg inline-flex items-center gap-1 transition">
                                                ⬇️ تنزيل
                                            </a>
                                            @can('evidence.review')
                                                @if($ef->status === 'pending')
                                                    <form action="{{ route('evidence.review', $ef->id) }}" method="POST" class="w-full">
                                                        @csrf
                                                        <textarea name="reviewer_notes" rows="2"
                                                            class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 mb-2 focus:ring-1 focus:ring-blue-400"
                                                            placeholder="ملاحظات (اختياري للموافقة، مطلوبة للإعادة)"></textarea>
                                                        <div class="flex gap-2">
                                                            <button type="submit" name="action" value="approve"
                                                                class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-2 rounded-lg transition">
                                                                ✅ قبول
                                                            </button>
                                                            <button type="submit" name="action" value="return"
                                                                class="flex-1 bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1.5 px-2 rounded-lg transition">
                                                                🔁 إعادة
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 italic text-sm">
                            <div class="text-4xl mb-2">📂</div>
                            لم يتم رفع أي ملفات إثبات لهذه الخطوة بعد.
                        </div>
                    @endif

                </div>
                {{-- ========== END EVIDENCE FILES TAB ========== --}}

            </div>

            {{-- Workflow Actions (Removed as workflow is handled at project level) --}}

            {{-- Actions --}}
            @if(in_array($step->project->status, ['draft', 'returned']))
                <div class="bg-white mt-6 rounded-2xl shadow border border-gray-100 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">

                        {{-- Edit Button --}}
                        <a href="{{ route('step.edit', $step->id) }}" class="flex items-center justify-center gap-1 w-full sm:w-auto
                                bg-[#1b5e20] text-white font-medium rounded-lg
                                px-4 py-2 shadow hover:bg-[#2e7d32] transition">
                            تعديل
                        </a>

                        {{-- Delete Form --}}
                        <form action="{{ route('step.destroy', $step->id) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذه الخطوة؟');" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center justify-center gap-1 w-full sm:w-auto
                                    text-red-700 border border-red-700 font-medium rounded-lg
                                    px-4 py-2 hover:bg-red-700 hover:text-white transition shadow-sm">
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>