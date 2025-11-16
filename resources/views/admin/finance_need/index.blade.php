<x-app-layout>

    <div class="p-6">

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4">إدارة قائمة الاحتياجات</h2>

            <!-- Add Form -->
            <form action="{{ route('admin.finance_need.store') }}" method="POST" class="mb-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block mb-1 font-medium">اسم الاحتياج *</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">الفئة (اختياري)</label>
                        <input type="text" name="category" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">الوصف (اختياري)</label>
                        <input type="text" name="description" class="w-full border rounded p-2">
                    </div>

                </div>

                <button class="mt-4 px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    إضافة الاحتياج
                </button>
            </form>

            <!-- Table List -->
            <h3 class="text-lg font-semibold mb-2">قائمة الاحتياجات</h3>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="p-3 text-right">#</th>
                            <th class="p-3 text-right">الاحتياج</th>
                            <th class="p-3 text-right">الفئة</th>
                            <th class="p-3 text-right">الوصف</th>
                            <th class="p-3 text-right">تاريخ الإضافة</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($needs as $need)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $need->id }}</td>
                                <td class="p-3">{{ $need->name }}</td>
                                <td class="p-3">{{ $need->category ?? '-' }}</td>
                                <td class="p-3">{{ $need->description ?? '-' }}</td>
                                <td class="p-3">{{ $need->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-3 text-center" colspan="5">لا توجد عناصر مضافة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</x-app-layout>
