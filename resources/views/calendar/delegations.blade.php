<x-app-layout title="إدارة التفويضات">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-3 rtl:space-x-reverse">
             <a href="{{ route('calendar.index') }}" class="text-gray-500 hover:text-emerald-600 transition">
                <span class="material-icons">arrow_forward</span>
            </a>
            <span class="material-icons text-3xl text-emerald-600">reduce_capacity</span>
            إدارة التفويضات والصلاحيات
        </h1>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- Grant Delegation Section --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-emerald-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                        <span class="material-icons">add_moderator</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">منح تفويض جديد</h2>
                        <p class="text-xs text-gray-500">اسمح لموظف آخر بإدارة تقويمك (إضافة/تعديل/حذف الأحداث).</p>
                    </div>
                </div>
                
                <form action="{{ route('delegations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اختر الموظف</label>
                        <select name="employee_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3" required>
                            <option value="" disabled selected>-- ابحث عن الموظف --</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg shadow transition flex justify-center items-center gap-2">
                        <span class="material-icons text-sm">check_circle</span>
                        منح الصلاحية
                    </button>
                </form>
            </div>

            {{-- My Delegations List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                    <h3 class="font-bold text-gray-700 flex items-center gap-2">
                        <span class="material-icons text-emerald-600 text-sm">list</span>
                        موظفون يديرون تقويمي
                    </h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($myDelegations as $delegation)
                        <div class="p-4 flex items-center justify-between group hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                    {{ mb_substr($delegation->employee->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $delegation->employee->name }}</p>
                                    <p class="text-xs text-gray-400">مفعل منذ {{ $delegation->granted_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            <form action="{{ route('delegations.destroy', $delegation->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء التفويض؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition" title="إلغاء التفويض">
                                    <span class="material-icons">remove_moderator</span>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            <p>لا يوجد تفويضات ممنوحة حالياً.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Delegated To Me --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 h-full">
                <div class="p-6 border-b border-gray-100 bg-blue-50 rounded-t-xl">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                            <span class="material-icons">assignment_ind</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">صلاحيات ممنوحة لي</h2>
                            <p class="text-xs text-blue-700">تقويمات يمكنك إدارتها بالنيابة عن الآخرين.</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 grid gap-4">
                    @forelse($delegatedToMe as $delegation)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition flex items-center justify-between bg-white">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <span class="material-icons">person</span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-lg">{{ $delegation->manager->name }}</p>
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold mt-1">نشط</span>
                                </div>
                            </div>
                            <a href="{{ route('calendar.index', ['year' => date('Y')]) }}?target_user={{ $delegation->manager_id }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition flex items-center gap-2">
                                <span class="material-icons text-sm">calendar_month</span>
                                فتح التقويم
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                <span class="material-icons text-4xl">inbox</span>
                            </div>
                            <p class="text-gray-500">لم يقم أحد بمنحك صلاحيات إدارة تقويمه.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>