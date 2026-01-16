<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('الخطوات المعلقة لي') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if($steps->isEmpty())
            <div class="bg-white shadow rounded text-center py-12">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('لا توجد خطوات معلقة') }}</h3>
                <p class="text-gray-500 mt-1">{{ __('ليس لديك أي خطوات تنتظر إجراءً منك حالياً') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($steps as $step)
                    <div
                        class="bg-white shadow rounded overflow-hidden border-r-4 {{ $step->status === 'returned' ? 'border-yellow-500' : 'border-indigo-500' }}">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-3">
                                <span
                                    class="px-2 py-1 rounded text-xs {{ $step->status === 'returned' ? 'bg-yellow-100 text-yellow-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $step->status_label }}
                                </span>
                                @if($step->priority && $step->priority <= 2)
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">{{ __('عاجل') }}</span>
                                @endif
                            </div>

                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('step.show', $step) }}" class="text-indigo-600 hover:underline">
                                    {{ $step->name }}
                                </a>
                            </h3>

                            <div class="text-sm text-gray-500 space-y-1 mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <span>{{ $step->workflow?->name ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <span>{{ $step->currentStage?->name ?? '-' }}</span>
                                </div>
                                @if($step->creator)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>{{ $step->creator->name }}</span>
                                    </div>
                                @endif
                                @if($step->due_date)
                                    <div class="flex items-center gap-2 {{ $step->due_date->isPast() ? 'text-red-600' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>{{ $step->due_date->format('Y-m-d') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 flex gap-2">
                            @if($step->currentStage?->can_approve)
                                <button type="button" onclick="openModal('approveModal{{ $step->id }}')"
                                    class="flex-1 bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">
                                    {{ __('موافقة') }}
                                </button>
                            @endif
                            @if($step->currentStage?->can_return)
                                <button type="button" onclick="openModal('returnModal{{ $step->id }}')"
                                    class="flex-1 bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">
                                    {{ __('إرجاع') }}
                                </button>
                            @endif
                            <button type="button" onclick="openModal('rejectModal{{ $step->id }}')"
                                class="flex-1 bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700">
                                {{ __('رفض') }}
                            </button>
                        </div>
                    </div>

                    {{-- Approve Modal --}}
                    <div id="approveModal{{ $step->id }}"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white w-full max-w-md rounded shadow p-6">
                            <div class="bg-green-100 -m-6 mb-4 p-4 rounded-t">
                                <h3 class="text-lg font-semibold text-green-800">{{ __('تأكيد الموافقة') }}</h3>
                            </div>
                            <form action="{{ route('workflow.approve', $step) }}" method="POST">
                                @csrf
                                <p class="mb-2">{{ __('هل تريد الموافقة على هذه الخطوة؟') }}</p>
                                <p class="font-bold mb-4">{{ $step->name }}</p>
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-1">{{ __('ملاحظات (اختياري)') }}</label>
                                    <textarea name="comments" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="closeModal('approveModal{{ $step->id }}')"
                                        class="flex-1 px-4 py-2 border rounded">
                                        {{ __('إلغاء') }}
                                    </button>
                                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded">
                                        {{ __('موافقة') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Return Modal --}}
                    <div id="returnModal{{ $step->id }}"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white w-full max-w-md rounded shadow p-6">
                            <div class="bg-yellow-100 -m-6 mb-4 p-4 rounded-t">
                                <h3 class="text-lg font-semibold text-yellow-800">{{ __('إرجاع الخطوة') }}</h3>
                            </div>
                            <form action="{{ route('workflow.return', $step) }}" method="POST">
                                @csrf
                                <p class="mb-2">{{ __('هل تريد إرجاع هذه الخطوة إلى المرحلة السابقة؟') }}</p>
                                <p class="font-bold mb-4">{{ $step->name }}</p>
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-1">{{ __('سبب الإرجاع') }}</label>
                                    <textarea name="comments" rows="2" class="w-full border rounded px-3 py-2"
                                        placeholder="{{ __('اشرح سبب إرجاع الخطوة...') }}"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="closeModal('returnModal{{ $step->id }}')"
                                        class="flex-1 px-4 py-2 border rounded">
                                        {{ __('إلغاء') }}
                                    </button>
                                    <button type="submit" class="flex-1 bg-yellow-500 text-white px-4 py-2 rounded">
                                        {{ __('إرجاع') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Reject Modal --}}
                    <div id="rejectModal{{ $step->id }}"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white w-full max-w-md rounded shadow p-6">
                            <div class="bg-red-100 -m-6 mb-4 p-4 rounded-t">
                                <h3 class="text-lg font-semibold text-red-800">{{ __('تأكيد الرفض') }}</h3>
                            </div>
                            <form action="{{ route('workflow.reject', $step) }}" method="POST">
                                @csrf
                                <div class="bg-red-50 text-red-700 px-4 py-3 rounded mb-4">
                                    <strong>{{ __('تحذير:') }}</strong>
                                    {{ __('الرفض سينهي سير العمل! هذا الإجراء لا يمكن التراجع عنه.') }}
                                </div>
                                <p class="font-bold mb-4">{{ $step->name }}</p>
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-1">{{ __('سبب الرفض') }} <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="comments" rows="3" required class="w-full border rounded px-3 py-2"
                                        placeholder="{{ __('يجب توضيح سبب الرفض...') }}"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="closeModal('rejectModal{{ $step->id }}')"
                                        class="flex-1 px-4 py-2 border rounded">
                                        {{ __('إلغاء') }}
                                    </button>
                                    <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded">
                                        {{ __('رفض') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }
            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>