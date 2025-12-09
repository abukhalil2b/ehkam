<x-app-layout title="إنشاء مستخدم جديد">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header --}}
        <header class="p-4 border-b border-gray-100 bg-white rounded-t-lg">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-2">
                <span class="material-icons text-3xl text-indigo-600">person_add</span>
                <p>إنشاء موظف جديد لحصر اسهامات المديريات في تحقيق المؤشرات</p>
            </h1>
        </header>

        {{-- Form --}}
        <div class="bg-white p-6 rounded-b-lg shadow-xl border border-t-0">
            <form action="{{ route('admin_users.store_for_sector') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section 1: Basic Data --}}
                <div class="space-y-4 border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700">1. البيانات الشخصية وبيانات الدخول</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                            <input type="text" name="name" required class="form-input w-full"
                                value="{{ old('name') }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                            <input type="email" name="email" required class="form-input w-full"
                                value="{{ old('email') }}">
                        </div>

                        {{-- Password --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                            <p class="text-sm text-gray-500">افتراضيا البريد الإلكتروني</p>
                        </div>

                    </div>
                </div>

                {{-- Section 2: Assign Sector --}}
                <div class="space-y-4 border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700">2. ربط المستخدم بالمديريات / القطاعات</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">اختر قطاع واحد</label>

                        <select name="sector_id[]"  required
                            class="form-multiselect w-full border-gray-300 rounded-md p-2">
                            @foreach ($sectors as $sector)
                                <option value="{{ $sector->id }}">
                                    {{ $sector->short_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4 border-t">
                    <button type="submit"
                        class="w-full py-2 px-4 rounded-md text-white bg-indigo-600 hover:bg-indigo-700 flex items-center justify-center">
                        <span class="material-icons text-lg -mt-1 rtl:ml-1">save</span>
                        حفظ وإنشاء المستخدم
                    </button>
                </div>
            </form>
        </div>

        {{-- Existing Users List --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow">

            <h2 class="text-xl font-bold text-gray-700 mb-4">المستخدمون المرتبطون بقطاعات</h2>

            @forelse ($users as $user)
                <div class="p-3 border-b flex justify-between">
                    <div>
                        <div class="font-semibold">{{ $user->name }}</div>
                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                    </div>

                    <div class="text-sm text-gray-800">
                        @foreach ($user->sectors as $sector)
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs">
                                {{ $sector->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-sm">لا يوجد مستخدمون حتى الآن</p>
            @endforelse

        </div>

    </div>

</x-app-layout>
