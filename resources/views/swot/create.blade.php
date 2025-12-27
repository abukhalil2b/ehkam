<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">إنشاء مشروع تحليل SWOT</h1>
            
            <form action="{{ route('swot.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        عنوان المشروع
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        required
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                        value="{{ old('title') }}"
                        placeholder="أدخل عنوان مشروع SWOT"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                    >
                        إنشاء المشروع
                    </button>
                    
                    <a 
                        href="{{ route('swot.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition"
                    >
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
