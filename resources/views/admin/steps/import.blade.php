<x-app-layout>
    <div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">استيراد خطوات العمل (3 Feet Plan)</h2>
    
    <form action="{{ route('admin.steps.import.store', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            {{ $project->title }}
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Select Excel/CSV File</label>
            <input type="file" name="file" accept=".csv,.xlsx" class="border p-2 w-full rounded" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Import Steps
        </button>
    </form>
</div>
</x-app-layout>
