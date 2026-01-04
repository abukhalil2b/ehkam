<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                الأهداف
            </h2>
            <button
                onclick="document.getElementById('createAimModal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
            >
                إضافة هدف
            </button>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">العنوان</th>
                        <th class="px-6 py-3 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aims as $aim)
                        <tr class="border-b">
                            <td class="px-6 py-3">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3">{{ $aim->title }}</td>
                            <td class="px-6 py-3 text-center flex justify-center gap-2">

                                <a href="{{ route('admin.aim.edit', $aim->id) }}"
                                   class="bg-yellow-500 text-white px-3 py-1 rounded">
                                    تعديل
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.aim.destroy', $aim->id) }}"
                                      onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded">
                                        حذف
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                                لا توجد أهداف
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createAimModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">

        <div class="bg-white w-full max-w-md rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4">إضافة هدف جديد</h3>

            <form method="POST" action="{{ route('admin.aim.store') }}">
                @csrf

                <input
                    type="text"
                    name="title"
                    required
                    class="w-full border rounded px-3 py-2 mb-4"
                    placeholder="عنوان الهدف"
                >

                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="document.getElementById('createAimModal').classList.add('hidden')"
                            class="px-4 py-2 border rounded">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
