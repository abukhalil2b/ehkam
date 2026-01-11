<li>
    <a href="{{ route('org_unit.edit', $unit->id) }}" class="org-node group">
        <div class="flex items-center gap-2 mb-2 justify-center">
            <span class="text-xs font-mono bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded border border-gray-200">
                {{ $unit->unit_code }}
            </span>
            <span class="text-[10px] uppercase text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded">
                {{ $unit->type }}
            </span>
        </div>

        <h3 class="font-bold text-gray-800 text-sm mb-2 group-hover:text-emerald-700 transition">
            {{ $unit->name }}
        </h3>

        <div class="border-t border-gray-100 pt-2 mt-2 flex justify-center items-center gap-3">
            <div class="flex items-center gap-1" title="عدد الوظائف">
                <span class="material-icons text-xs text-gray-400">work</span>
                <span class="text-xs font-bold text-gray-600">{{ $unit->positions->count() }}</span>
            </div>
            <div class="flex items-center gap-1" title="عدد الموظفين">
                <span class="material-icons text-xs text-gray-400">people</span>
                <span class="text-xs font-bold text-gray-600">
                    {{ $unit->positions->sum(function ($pos) {
    return $pos->currentEmployees->count(); }) }}
                </span>
            </div>
        </div>

        <div class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="bg-emerald-500 text-white p-1 rounded-full shadow-md flex items-center justify-center">
                <span class="material-icons text-[14px]">edit</span>
            </span>
        </div>
    </a>

    @if($unit->children->count() > 0)
        <ul>
            @foreach($unit->children as $child)
                @include('org_units.partials.tree-recursive', ['unit' => $child])
            @endforeach
        </ul>
    @endif
</li>