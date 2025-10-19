<x-app-layout>
    <x-slot name="header">
       <div class="text-xl font-extrabold text-gray-900 tracking-tight"> قائمة أسئلة التقييم {{ $currentYear }} . عدد الأسئلة {{ count($questions) }} </div>
    </x-slot>

    <!-- Load SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <div class="container py-8 mx-auto px-4" x-data="sortableQuestions('{{ route('assessment_questions.update_ordered') }}')">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div class="flex items-center space-x-3 space-x-reverse">
                <a href="{{ route('activity.index') }}"
                    class="px-4 py-2 text-sm font-semibold  rounded-lg shadow-sm transition duration-150 ease-in-out focus:outline-none bg-blue-500 hover:bg-blue-700 text-white">
                    <i class="fas fa-list-alt ml-2"></i> الأنشطة
                </a>

                <a href="{{ route('assessment_questions.create') }}"
                    class="px-4 py-2 text-sm font-semibold text-white bg-purple-600 rounded-lg shadow-lg hover:bg-purple-700 transition duration-150 ease-in-out">
                    <i class="fas fa-plus ml-2"></i> إنشاء سؤال جديد
                </a>
            </div>
        </div>

        <!-- Notification Message -->
        <div x-show="message"
            :class="{ 'bg-green-100 text-green-800': isSuccess, 'bg-red-100 text-red-800': !isSuccess }"
            class="p-4 rounded-lg mb-4 transition-all duration-300" x-cloak>
            <span x-text="message"></span>
        </div>

        <!-- Loading Indicator -->
        <div x-show="isLoading" class="flex items-center space-x-2 text-purple-600 mb-4" x-cloak>
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span>جارٍ تحديث الترتيب...</span>
        </div>

        @if ($questions->isEmpty())
            <div class="p-6 text-center bg-gray-50 border border-dashed rounded-lg">
                <p class="text-lg text-gray-500">لم يتم إنشاء أي أسئلة تقييم بعد.</p>
                <a href="{{ route('assessment_questions.create') }}"
                    class="text-purple-600 hover:text-purple-800 mt-2 inline-block font-medium">ابدأ بإضافة أول
                    سؤال.</a>
            </div>
        @else
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- New column for drag handle -->
                            <th
                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                الترتيب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                السؤال</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                النوع</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                الحد الأقصى</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                الإجراءات</th>
                                <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                التاريخ</th>
                        </tr>
                    </thead>
                    <!-- The container for SortableJS -->
                    <tbody x-ref="sortableContainer" class="bg-white divide-y divide-gray-200">
                        @foreach ($questions as $question)
                            <!-- The data-id is crucial for identifying the item during reordering -->
                            <tr data-id="{{ $question->id }}"
                                class="group cursor-grab hover:bg-purple-50 transition duration-150">
                                <!-- Drag Handle Column -->
                                <td class="px-3 py-4 text-gray-400 w-12 text-center align-middle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 mx-auto group-hover:text-purple-600 transition duration-150">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 9H16.5M3.75 15H16.5M3.75 21H16.5" />
                                    </svg>
                                </td>

                                <td class="px-6 py-4 text-gray-900 font-medium">
                                    {{ $question->content }}
                                    <div class="text-xs text-gray-400">{{ $question->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $question->type == 'range' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $question->type == 'range' ? 'نقاط' : 'نص' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $question->max_point ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('assessment_questions.edit', $question->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                </td>
                                <td class="px-6 py-4 text-gray-900 font-medium">
                                    {{ $question->created_at->format('d-m-Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sortableQuestions', (url) => ({
                isLoading: false,
                message: '',
                isSuccess: true,
                url: url,

                init() {
                    new Sortable(this.$refs.sortableContainer, {
                        animation: 150,
                        handle: 'svg', // Use the drag handle icon as the grab handle
                        onEnd: (evt) => {
                            this.updateOrder();
                        },
                    });
                },

                // Collects the current order and sends the AJAX request
                updateOrder() {
                    const sortedElements = Array.from(this.$refs.sortableContainer.children);
                    // Extract data-id from each row to get the new order of IDs
                    const orderIds = sortedElements.map(el => el.dataset.id).join(',');

                    // Clear previous message
                    this.message = '';
                    this.isLoading = true;

                    // Note: While the user requested a GET, POST is generally better for updates.
                    // Sticking to GET as requested, passing IDs as a query parameter.
                    fetch(`${this.url}?orderIds=${orderIds}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                // IMPORTANT: Include CSRF token if running in a standard Laravel environment with POST/PUT/DELETE
                                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            this.isLoading = false;
                            if (!response.ok) {
                                return response.json().then(error => {
                                    throw new Error(error.message || 'فشل تحديث الترتيب')
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            this.isSuccess = true;
                            this.message = data.message || 'تم تحديث ترتيب الأسئلة بنجاح.';
                            this.hideMessage();
                        })
                        .catch(error => {
                            this.isLoading = false;
                            this.isSuccess = false;
                            this.message = error.message;
                            this.hideMessage();
                        });
                },

                // Helper to hide message after a delay
                hideMessage() {
                    setTimeout(() => {
                        this.message = '';
                    }, 3000);
                }
            }))
        })
    </script>
</x-app-layout>
