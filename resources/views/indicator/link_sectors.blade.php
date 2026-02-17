<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">ربط القطاعات بالمؤشر: {{ $indicator->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('indicator.sectors.update', $indicator) }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                @csrf
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">اختر القطاعات المسؤولة عن هذا المؤشر</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($allSectors as $sector)
                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors {{ in_array($sector->id, $currentSectorIds) ? 'border-indigo-500 bg-indigo-50/30' : 'border-gray-200' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sectors[]" value="{{ $sector->id }}" 
                                           {{ in_array($sector->id, $currentSectorIds) ? 'checked' : '' }}
                                           class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="mr-3 text-sm">
                                    <span class="font-bold text-gray-700">{{ $sector->name }}</span>
                                    <p class="text-gray-400 text-xs">{{ $sector->code }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="px-8 py-4 bg-gray-50 flex justify-between items-center">
                    <a href="{{ route('indicator.show', $indicator) }}" class="text-sm text-gray-500 hover:text-gray-700">إلغاء</a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                        حفظ ومتابعة ضبط خطوط الأساس ←
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>