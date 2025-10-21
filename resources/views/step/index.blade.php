<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800">{{ $project->title }}</h1>
    </x-slot>

    <div x-data="{ open: false }" class="container mx-auto py-8 px-4">

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

            @php
                $phases = [
                    'preparation' => ['title' => 'التحضير', 'weight' => '15%'],
                    'planning' => ['title' => 'التخطيط والتطوير', 'weight' => '20%'],
                    'implementation' => ['title' => 'التنفيذ', 'weight' => '30%'],
                    'review' => ['title' => 'المراجعة', 'weight' => '20%'],
                    'approval' => ['title' => 'الاعتماد والإغلاق', 'weight' => '15%'],
                ];
            @endphp

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
                                <tr
                                    class="{{ $step->status === 'delayed' ? 'bg-red-50' : 'bg-white' }} hover:bg-gray-50 transition-colors">
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
                                        @switch($step->status)
                                            @case('delayed')
                                                <span
                                                    class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <i class="fas fa-exclamation-circle ml-1"></i> متأخر
                                                </span>
                                            @break

                                            @case('completed')
                                                <span
                                                    class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <i class="fas fa-check-circle ml-1"></i> منجز
                                                </span>
                                            @break

                                            @case('in_progress')
                                            @case('in-progress')
                                                <span
                                                    class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <i class="fas fa-spinner ml-1"></i> في الإجراء
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <i class="far fa-clock ml-1"></i> لم يبدأ
                                                </span>
                                        @endswitch
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
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
                @click.self="open = false" @keydown.escape.window="open = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <form method="POST" action="{{ route('step.store', $project->id) }}"
                    class="bg-white w-full max-w-2xl rounded-lg shadow-xl overflow-hidden transform transition-all"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    @csrf

                    <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white px-6 py-4">
                        <h3 class="text-lg font-bold">إضافة خطوة عمل جديدة</h3>
                    </div>

                    <div class="p-6 space-y-4">
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
                                <option value="preparation">التحضير</option>
                                <option value="planning">التخطيط والتطوير</option>
                                <option value="implementation">التنفيذ</option>
                                <option value="review">المراجعة</option>
                                <option value="approval">الإعتماد والإغلاق</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الوثائق الداعمة</label>
                            <textarea name="supporting_documents" rows="3"
                                class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-green-500 focus:border-green-500"
                                placeholder="أدخل الوثائق الداعمة (اختياري)"></textarea>
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
