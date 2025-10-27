<x-app-layout title="إدارة الهيكل التنظيمي">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('tree', {
                expandAll: false
            })
        })
    </script>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-indigo-600">account_tree</span>
            إدارة الهيكل التنظيمي
        </h1>
    </x-slot>

    <div class="p-4 bg-gray-50 min-h-screen" x-data="{ expandAll: true }">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-700 flex items-center space-x-2 rtl:space-x-reverse">
                <span class="material-icons">apartment</span>
                الهيكل التنظيمي
            </h3>

            <button @click="$store.tree.expandAll = !$store.tree.expandAll"
                class="bg-blue-600 text-white px-4 py-1 rounded-lg text-sm hover:bg-blue-700 transition">
                <span x-text="$store.tree.expandAll ? 'طيّ الكل' : 'توسيع الكل'"></span>
            </button>

        </div>

        <div class="border border-dashed border-blue-300 p-4 rounded-lg bg-white">
            @forelse ($topLevelUnits as $unit)
                @include('admin_structure.partials._unit-tree-item', ['unit' => $unit, 'depth' => 0])
            @empty
                <p class="text-center text-gray-500">الرجاء إضافة أول مديرية عامة.</p>
            @endforelse
        </div>
    </div>

</x-app-layout>
