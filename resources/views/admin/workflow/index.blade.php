<x-app-layout title="لوحة تحكم سير العمل">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-purple-600">dashboard_customize</span>
            <span>لوحة تحكم سير العمل </span>
        </h1>
    </x-slot>

    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto px-4">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-700">كل العمليات الجارية (Activities & Steps)</h3>
                        <span class="bg-purple-100 text-purple-800 text-sm font-bold px-3 py-1 rounded-full">
                            الإجمالي: {{ $allWorkflows->count() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">النوع</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">العنوان</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">المرحلة الحالية</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">الحالة</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">المسند إليه</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">آخر تحديث</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($allWorkflows as $workflow)
                                                                <tr class="hover:bg-gray-50 transition">
                                                                    <td class="px-4 py-3">
                                                                        @if($workflow['type'] === 'Activity')
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                                نشاط (Activity)
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                                خطوة (Step)
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-4 py-3 font-bold text-gray-800">{{ $workflow['name'] }}</td>
                                                                    <td class="px-4 py-3">
                                                                        {{ match ($workflow['stage']) {
                                        'target_setting' => 'تحديد المستهدفات',
                                        'execution' => 'التنفيذ',
                                        'verification' => 'التحقق والمراجعة',
                                        'approval' => 'الاعتماد النهائي',
                                        'completed' => 'مكتمل',
                                        default => $workflow['stage']
                                    } }}
                                                                    </td>
                                                                    <td class="px-4 py-3">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                {{ match ($workflow['status']) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }}">
                                                                            {{ match ($workflow['status']) {
                                        'pending' => 'قيد الانتظار',
                                        'approved' => 'معتمد',
                                        'rejected' => 'مرفوض',
                                        default => $workflow['status']
                                    } }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-gray-600">{{ $workflow['assigned_to'] }}</td>
                                                                    <td class="px-4 py-3 text-gray-500" dir="ltr">
                                                                        {{ optional($workflow['last_updated'])->diffForHumans() ?? '—' }}</td>
                                                                    <td class="px-4 py-3 text-center">
                                                                        <a href="{{ $workflow['link'] }}"
                                                                            class="text-indigo-600 hover:text-indigo-900 font-bold text-xs border border-indigo-200 px-3 py-1 rounded hover:bg-indigo-50 transition">
                                                                            عرض التفاصيل
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            لا توجد أي عمليات سير عمل نشطة حالياً.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>