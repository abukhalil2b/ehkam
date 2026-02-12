<x-app-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="min-h-screen bg-[#f8fafc] pb-12" x-data="{
        openCreateModal: false,
        openEditModal: false,
        editStage: {},
        resetEditModal() {
            this.editStage = {};
            this.openEditModal = false;
        },
        loadEditModal(stage) {
            this.editStage = { ...stage };
            this.openEditModal = true;
        }
    }">
        <div class="bg-white border-b border-slate-200 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all border border-slate-200">
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">مراحل التقييم</h1>
                            <p class="text-slate-500 text-sm mt-1">تحديد وتسلسل خطوات تقييم المشاريع</p>
                        </div>
                    </div>
                    <button @click="openCreateModal = true"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        إضافة مرحلة جديدة
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($stages as $index => $stage)
                    <div
                        class="group relative bg-white rounded-2xl border border-slate-200 p-5 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-lg">
                                {{ $index + 1 }}
                            </div>

                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="loadEditModal({{ json_encode($stage) }})"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <form action="{{ route('assessment_stages.destroy', $stage) }}" method="POST"
                                    onsubmit="return confirm('{{ $stage->results_count > 0 ? 'تحذير: هذه المرحلة تحتوي على ' . $stage->results_count . ' تقييمات مرسلة. حذف المرحلة سيؤدي لمسح كافة هذه البيانات نهائياً! هل أنت متأكد؟' : 'هل أنت متأكد من حذف هذه المرحلة؟' }}');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <h3 class="font-bold text-slate-800 text-lg group-hover:text-indigo-700 transition-colors">
                            {{ $stage->title }}
                        </h3>

                        <div class="mt-4 flex flex-col gap-2">
                            <div class="flex items-center text-xs text-slate-400 font-medium uppercase tracking-wider">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 ml-2"></span>
                                نشط
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($stage->results_count > 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z">
                                            </path>
                                        </svg>
                                        {{ $stage->results_count }} تقييم تم إرساله
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        لا توجد تقييمات بعد
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">لا توجد مراحل حالياً</h3>
                        <p class="text-slate-500 max-w-xs text-center mt-2 mb-6">قم بإضافة مراحل التقييم لتتمكن من تنظيم
                            سير العمل في المشاريع.</p>
                        <button @click="openCreateModal = true"
                            class="px-6 py-2 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition-all">
                            إضافة المرحلة الأولى
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="openCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openCreateModal" x-transition.opacity
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="openCreateModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="openCreateModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                    <form action="{{ route('assessment_stages.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-6 py-8">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">إضافة مرحلة جديدة</h3>
                            <p class="text-slate-500 text-sm mb-6">أدخل اسم المرحلة التعليمية أو التقييمية الجديدة.</p>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">اسم المرحلة</label>
                                <input type="text" name="title" required placeholder="مثال: السنوي - النصف السنوي"
                                    class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all">
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit"
                                class="px-5 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md">حفظ
                                المرحلة</button>
                            <button type="button" @click="openCreateModal = false"
                                class="px-5 py-2 bg-white text-slate-600 font-semibold border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openEditModal" x-transition.opacity
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="resetEditModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="openEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                    <form :action="`{{ url('assessment_stages/update') }}/${editStage.id}`" method="POST">
                        @csrf @method('PUT')
                        <div class="bg-white px-6 py-8">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">تعديل المرحلة</h3>
                            <p class="text-slate-500 text-sm mb-6">قم بتعديل المسمى الخاص بهذه المرحلة.</p>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">اسم المرحلة</label>
                                <input type="text" name="title" x-model="editStage.title" required
                                    class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all">
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit"
                                class="px-5 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md">تحديث
                                البيانات</button>
                            <button type="button" @click="resetEditModal()"
                                class="px-5 py-2 bg-white text-slate-600 font-semibold border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
