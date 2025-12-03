<x-app-layout title="إنشاء مستخدم جديد">

    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header --}}
        <header class="p-4 border-b border-gray-100 bg-white rounded-t-lg">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-2">
                <span class="material-icons text-3xl text-blue-600">link</span>
                <p>ربط مستخدم بقطاع / مديرية</p>
            </h1>
              <p class="mt-3 text-gray-800 text-xs">
                    حصر إسهامات (المديريات والإدارات الإقليمية للأوقاف والشؤون الدينية) في تحقيق مستهدفات المؤشرات
                    الرئيسية للوزارة
                </p>
        </header>

        {{-- User Info --}}
        <div class="bg-white p-6 border border-t-0 shadow rounded-b-lg space-y-6">

            {{-- User Card --}}
            <div class="p-4 bg-gray-50 rounded-lg border">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center rtl:space-x-reverse space-x-2">
                    <span class="material-icons text-indigo-600">person</span>
                    بيانات المستخدم
                </h2>

                <div class="mt-3 text-gray-800">
                    <div><strong>الاسم:</strong> {{ $user->name }}</div>
                    <div><strong>البريد الإلكتروني:</strong> {{ $user->email }}</div>
                    <div>
                        <strong>القطاعات الحالية:</strong>

                        @if ($user->sectors->count() > 0)
                            @foreach ($user->sectors as $sector)
                                <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-md">
                                    {{ $sector->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-sm">غير مرتبط بأي قطاع</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Link Form --}}
            <form action="{{ route('admin_users.link_user_with_sector_store', $user) }}" method="POST"
                class="space-y-6">
                @csrf

                <div class="space-y-4">

                    <h2 class="text-lg font-semibold text-gray-700">اختر القطاعات لربط المستخدم بها</h2>

                    <select name="sector_id[]" multiple required
                        class="form-multiselect w-full border-gray-300 rounded-md p-2">
                        @foreach ($sectors as $sector)
                            <option value="{{ $sector->id }}" @if ($user->sectors->contains($sector->id)) selected @endif>
                                {{ $sector->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-sm text-gray-500">ملاحظة: يمكنك اختيار أكثر من قطاع.</p>
                </div>

                {{-- Submit --}}
                <div class="pt-4 border-t">
                    <button type="submit"
                        class="w-full py-2 px-4 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition flex items-center justify-center">
                        <span class="material-icons text-lg -mt-1 rtl:ml-1">save</span>
                        حفظ وربط المستخدم
                    </button>
                </div>
            </form>
        </div>

    </div>


</x-app-layout>
