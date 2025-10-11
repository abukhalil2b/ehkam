<x-app-layout>
   <x-slot name="header">
    إنشاء نشاط
   </x-slot>

    <div class="container py-8 mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">إنشاء نشاط جديد</h2>

        <form method="POST" action="{{ route('activity.store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">عنوان النشاط:</label>
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="project_id" class="block text-gray-700 text-sm font-bold mb-2">المشروع:</label>
                <select name="project_id" id="project_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">-- اختر مشروع --</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    حفظ النشاط
                </button>
            </div>
        </form>
    </div>
</x-app-layout>