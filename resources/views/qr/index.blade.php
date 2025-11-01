<x-app-layout title="رموز QR">
    {{-- Make sure Alpine is loaded in your layout (example below) --}}
    <div class="p-6" x-data="{ open: false }">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">رموز QR</h1>

            <!-- button inside same x-data scope -->
            <button
                @click="open = true"
                type="button"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                إضافة رمز جديد
            </button>
        </div>

    
        <div class="grid md:grid-cols-3 gap-4">
            @forelse($qrCodes as $item)
                <div class="p-4 border rounded-lg bg-white shadow-sm">
                  
                    <p class="text-sm text-gray-700 break-all">{{ $item->content }}</p>
                    <div class="text-xs text-gray-500 mt-1">
                        <span>بواسطة:</span> {{ $item->author->name ?? 'غير معروف' }}
                    </div>

                    <div class="flex justify-between mt-3">
                        <a href="{{ route('qr.show', $item->id) }}" class="text-blue-600 text-sm hover:underline">عرض</a>

                        <form action="{{ route('qr.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 text-sm hover:underline">حذف</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-500">لا توجد رموز QR حالياً.</p>
            @endforelse
        </div>

        <!-- Modal (still inside same x-data) -->
        <div
            x-cloak
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div @click.away="open = false" class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <h2 id="modal-title" class="text-xl font-bold mb-4">إضافة رمز QR جديد</h2>

                <form action="{{ route('qr.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700">الرابط أو المحتوى</label>
                        <input
                            type="url"
                            name="content"
                            value="{{ old('content') }}"
                            required
                            placeholder="https://example.com"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                        >
                        @error('content')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 rounded-lg">إلغاء</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
