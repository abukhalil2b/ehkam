<x-app-layout>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Optional: Improve RTL and Tailwind look */
            .select2-container .select2-selection--single {
                height: 42px;
                border-radius: 0.5rem;
                border: 1px solid #d1d5db;
                /* Tailwind gray-300 */
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                text-align: right;
                padding-right: 1rem;
                color: #374151;
                /* Tailwind gray-700 */
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                left: 0.5rem;
                right: auto;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                $('#org_unit').select2({
                    placeholder: 'اختر المنفذ',
                    allowClear: true,
                    dir: 'rtl',
                    width: '100%'
                });
            });
        </script>
    @endpush


    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800">{{ $project->title }}</h1>
        <div class="py-2">
            @foreach ($activities as $activityItem)
                <a href="{{ route('step.index', ['project' => $project->id, 'activity' => $activityItem->id]) }}"
                    class="inline-block px-3 py-1 rounded
                    {{ request()->route('activity') == $activityItem->id
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-100 hover:bg-gray-200' }}">
                    {{ $activityItem->title }}
                </a>
            @endforeach
            <a href="{{ route('step.index', ['project' => $project->id]) }}"
                class="inline-block px-3 py-1 rounded bg-blue-100 hover:bg-blue-200 font-semibold">
                عرض الكل
            </a>
        </div>
    </x-slot>

    <div class="px-4">
        <a href="{{ route('project.index', $indicator->id) }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            المشروع
        </a>
    </div>

    <div x-data="{ open: false, is_need_evidence_file: false, is_need_to_put_target: false }" class="container mx-auto py-8 px-4">
        <!-- Work Steps Section -->
        <section class="p-6 bg-white rounded-2xl shadow-md border border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h2 class="text-xl font-bold text-green-700 mb-4 sm:mb-0">خطوات العمل</h2>

                <button @click="open = true" type="button"
                    class="flex items-center gap-2 text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span>إضافة خطوة جديدة</span>
                </button>
            </div>

            <!-- Steps Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-600 border border-gray-200 rounded-lg">
                    <thead class="text-xs text-gray-700 bg-gray-100">
                        <tr>
                            <th class="p-3 w-12">#</th>
                            <th class="px-6 py-3">الاسم</th>
                            <th class="px-6 py-3">من</th>
                            <th class="px-6 py-3">إلى</th>
                            <th class="px-6 py-3">المستهدف</th>
                            <th class="px-6 py-3">الحالة</th>
                            <th class="px-6 py-3">عمليات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($phases as $phaseKey => $meta)
                            <tr>
                                <td colspan="7" class="p-0">
                                    <div
                                        class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
                                        <span>{{ $meta['title'] }}</span>
                                        <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                            الوزن: {{ $meta['weight'] }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @forelse ($steps->where('phase', $phaseKey) as $index => $step)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="p-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $step->name }}</td>
                                    <td class="px-6 py-4">
                                        {{ $step->start_date ? \Carbon\Carbon::parse($step->start_date)->format('d-m-Y') : '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $step->end_date ? \Carbon\Carbon::parse($step->end_date)->format('d-m-Y') : '—' }}
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($step->target_percentage ?? 0, 2) }}%</td>
                                    <td class="px-6 py-4">
                                        @include('step.partials.step-status')
                                    </td>
                                    <td class="px-6 py-4 space-y-1">
                                        <a href="{{ route('step.show', $step->id) }}"
                                            class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 font-medium rounded-lg text-xs px-2 py-1 transition">
                                            عرض
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-gray-50">
                                    <td colspan="7" class="text-center py-4 text-gray-400 text-sm">
                                        لا توجد خطوات في هذه المرحلة.
                                    </td>
                                </tr>
                            @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modal -->
        <div x-show="open" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-auto"
            @click.self="open = false" @keydown.escape.window="open = false"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <form method="POST" action="{{ route('step.store', $project->id) }}"
                class="bg-white w-full max-w-2xl rounded-lg shadow-xl overflow-hidden transform transition-all"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                x-data="{ activeTab: 'step', is_need_evidence_file: false, is_need_to_put_target: false }">
                @csrf

                <div
                    class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold">إضافة خطوة عمل جديدة</h3>
                    <span>المستهدف: {{ $indicator->target_for_indicator }}</span>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 border-b border-gray-200 flex gap-4">
                    <button type="button" @click="activeTab = 'step'"
                        :class="activeTab === 'step' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2">الخطوة</button>
                    <button type="button" @click="activeTab = 'files'"
                        :class="activeTab === 'files' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2">الملفات الداعمة</button>
                    <button type="button" @click="activeTab = 'target'"
                        :class="activeTab === 'target' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2">المستهدف</button>

                </div>

                <div class="p-6 space-y-4">
                    <!-- Step Details Tab -->
                    <div x-show="activeTab === 'step'" x-cloak class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الأنشطة</label>
                            <select id="activity_id" name="activity_id" class="w-full rounded border-gray-300">
                                @foreach ($activities as $activity)
                                    <option value="{{ $activity->id }}">{{ $activity->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم الخطوة</label>
                            <input name="name" type="text" required
                                class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-green-500 focus:border-green-500"
                                placeholder="أدخل اسم الخطوة">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">من</label>
                                <input name="start_date" type="date"
                                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">إلى</label>
                                <input name="end_date" type="date"
                                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">المرحلة</label>
                            <select name="phase" required
                                class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-green-500 focus:border-green-500">
                                <option value="" disabled selected>اختر المرحلة</option>
                                @foreach ($phases as $key => $phase)
                                    <option value="{{ $key }}">{{ $phase['title'] }} -
                                        {{ $phase['weight'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Supporting Files Tab -->
                    <div x-show="activeTab === 'files'" x-cloak class="space-y-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" x-model="is_need_evidence_file" name="is_need_evidence_file"
                                value="1" class="rounded border-gray-300 text-[#1b5e20]">
                            <label class="font-semibold text-[#1b5e20]">هل تتطلب ملفات داعمة؟</label>
                        </div>

                        <template x-if="is_need_evidence_file">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الوثائق الداعمة</label>
                                <input name="supporting_document"
                                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-green-500 focus:border-green-500"
                                    placeholder="أدخل الوثائق الداعمة (مثال:محضر إجتماع)"></input>
                            </div>
                        </template>
                    </div>

                    <!-- Contribute Target Tab -->
                    <div x-show="activeTab === 'target'" x-cloak class="space-y-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" x-model="is_need_to_put_target" name="is_need_to_put_target"
                                value="1" class="rounded border-gray-300 text-[#1b5e20]">
                            <span class="font-semibold text-[#1b5e20]">هل تغذي المؤشر؟</span>
                            <template x-if="is_need_to_put_target">
                                <span class="text-green-700 font-semibold">نعم</span>
                            </template>
                        </label>

                        <template x-if="is_need_to_put_target">
                            <div class="mt-3 space-y-4">
                                <!-- Organizational Units -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">المنفذون</label>
                                    <select id="org_unit" name="org_unit_ids[]" multiple
                                        class="w-full rounded border-gray-300">
                                        @foreach ($org_units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}
                                                ({{ $unit->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Period Templates with Target Inputs -->
                                @if ($periodTemplates->count() > 0)
                                    <div class="border-t pt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">توزيع
                                            المستهدف</label>

                                        <div class="space-y-2 max-h-56 overflow-y-auto">
                                            @foreach ($periodTemplates as $period)
                                                <div
                                                    class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-200">
                                                    <div class="flex-1">
                                                        <span
                                                            class="font-medium text-gray-800">{{ $period->name ?? 'فترة غير مسماة' }}</span>
                                                        <span
                                                            class="text-gray-500 text-xs">({{ $period->cate }})</span>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <input type="number" step="0.01" min="0"
                                                            name="period_targets[{{ $period->id }}]"
                                                            class="w-28 border border-gray-300 rounded-md p-1.5 text-sm focus:ring-green-500 focus:border-green-500"
                                                            placeholder="المستهدف">
                                                        <span class="text-gray-600 text-xs">%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3 text-gray-500 text-sm">لا توجد فترات زمنية مرتبطة بهذا المؤشر.
                                    </div>
                                @endif
                            </div>
                        </template>
                    </div>
                </div>

                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t border-gray-200">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-gray-700">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-md text-white bg-green-600 hover:bg-green-700">
                        حفظ
                    </button>
                </div>
            </form>
        </div>


    </div>
</x-app-layout>
