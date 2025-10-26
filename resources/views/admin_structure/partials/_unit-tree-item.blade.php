@php $hasChildren = $unit->children->isNotEmpty(); @endphp

<div 
    x-data="{ open: $store.tree.expandAll }"
    x-effect="open = $store.tree.expandAll"
    class="mb-1">

    <div class="flex items-center gap-2 cursor-pointer py-1"
         style="padding-right: {{ $depth * 20 }}px;"
         @click.stop="open = !open">

        @if ($hasChildren)
            <span class="material-icons transition-transform duration-200" :class="open ? 'rotate-90' : ''">chevron_right</span>
        @else
            <span class="w-4 inline-block"></span>
        @endif

        <span class="material-icons text-blue-600">
            {{ $unit->type === 'Directorate' ? 'apartment' : 'category' }}
        </span>

        <span class="font-semibold">{{ $unit->name }}</span>
        <span class="text-gray-400 text-xs">[{{ $unit->type }}]</span>
    </div>

    @if ($hasChildren)
        <div x-show="open" x-transition class="mt-1">
            @foreach ($unit->children as $child)
                @include('admin_structure.partials._unit-tree-item', [
                    'unit' => $child,
                    'depth' => $depth + 1,
                ])
            @endforeach
        </div>
    @endif
</div>
