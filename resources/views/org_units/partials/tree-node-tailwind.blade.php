@php
    $nodeColors = [
        'Minister' => 'bg-gradient-to-br from-green-500 to-green-600 text-white border-green-600',
        'Undersecretary' => 'bg-gradient-to-br from-teal-500 to-teal-600 text-white border-teal-600',
        'Directorate' => 'bg-gradient-to-br from-cyan-500 to-cyan-600 text-white border-cyan-600',
        'Department' => 'bg-gradient-to-br from-yellow-400 to-yellow-500 text-gray-900 border-yellow-500',
        'Section' => 'bg-white text-gray-800 border-gray-300',
        'Expert' => 'bg-gradient-to-br from-purple-500 to-purple-600 text-white border-purple-600',
    ];
    $colorClass = $nodeColors[$unit->type] ?? 'bg-white text-gray-800 border-gray-300';
@endphp

<div class="org-unit-node">
    <!-- Node Card -->
    <div class="flex justify-center mb-4">
        <div class="node-content relative {{ $colorClass }} border-2 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-4 w-64"
             data-unit-id="{{ $unit->id }}">
            
            @if($unit->children->count() > 0)
                <button type="button" 
                        class="toggle-btn absolute top-2 left-2 w-6 h-6 rounded-full bg-black/10 hover:bg-black/20 flex items-center justify-center transition-colors"
                        onclick="toggleChildren(this)">
                    <i class="toggle-icon fas fa-chevron-down text-xs"></i>
                </button>
            @endif

            <div class="text-center">
                <h5 class="font-bold text-base mb-1 leading-tight">{{ $unit->name }}</h5>
                <div class="text-xs opacity-80 font-mono">{{ $unit->unit_code }}</div>
                <div class="text-xs uppercase tracking-wide mt-1 opacity-90 font-semibold">{{ $unit->type }}</div>
            </div>
            
            @if($unit->positions->count() > 0)
                <div class="mt-3 pt-3 border-t {{ $unit->type === 'Department' ? 'border-yellow-600' : ($unit->type === 'Section' ? 'border-gray-300' : 'border-white/30') }}">
                    <div class="flex items-center justify-center gap-2 text-xs">
                        <i class="fas fa-users"></i>
                        <span>
                            {{ $unit->positions->sum(function($position) {
                                return $position->employees->count();
                            }) }} موظف
                        </span>
                        <span class="opacity-70">في</span>
                        <span>{{ $unit->positions->count() }} وظيفة</span>
                    </div>
                </div>
            @endif

            @if($unit->positions->count() > 0 && $level < 3)
                <div class="mt-3 pt-3 border-t {{ $unit->type === 'Department' ? 'border-yellow-600' : ($unit->type === 'Section' ? 'border-gray-300' : 'border-white/30') }} text-right space-y-2">
                    @foreach($unit->positions->take(3) as $position)
                        <div class="text-xs">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-briefcase mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1 text-right">
                                    <div class="font-semibold">{{ $position->title }}</div>
                                    @if($position->employees->count() > 0)
                                        <div class="mt-1 space-y-1">
                                            @foreach($position->employees as $assignment)
                                                <div class="flex items-center gap-1 opacity-90 justify-end">
                                                    <span>{{ $assignment->user->name ?? 'N/A' }}</span>
                                                    <i class="fas fa-user text-[10px]"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-xs opacity-70 italic">شاغرة</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($unit->positions->count() > 3)
                        <div class="text-xs opacity-70 text-center">
                            + {{ $unit->positions->count() - 3 }} وظيفة أخرى
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Children Container -->
    @if($unit->children->count() > 0)
        <div class="children-wrapper">
            <!-- Vertical connector line from parent -->
            <div class="flex justify-center">
                <div class="w-0.5 h-8 bg-gray-300"></div>
            </div>

            <!-- Horizontal line connecting children -->
            <div class="relative">
                @if($unit->children->count() > 1)
                    <div class="absolute top-0 right-0 left-0 h-0.5 bg-gray-300" 
                         style="width: calc(100% - {{ 100 / $unit->children->count() / 2 }}% - {{ 100 / $unit->children->count() / 2 }}%); 
                                margin-right: {{ 100 / $unit->children->count() / 2 }}%; 
                                margin-left: {{ 100 / $unit->children->count() / 2 }}%;"></div>
                @endif

                <!-- Children nodes in grid -->
                <div class="grid gap-8 pt-8" 
                     style="grid-template-columns: repeat({{ min($unit->children->count(), 4) }}, 1fr);">
                    @foreach($unit->children as $child)
                        <div class="relative">
                            <!-- Vertical connector from horizontal line to child -->
                            <div class="absolute top-0 right-1/2 w-0.5 -translate-y-8 bg-gray-300" style="height: 2rem;"></div>
                            
                            @include('org_units.partials.tree-node-tailwind', ['unit' => $child, 'level' => $level + 1])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@if($level === 0)
@push('scripts')
<script>
function toggleChildren(button) {
    const node = button.closest('.org-unit-node');
    const childrenWrapper = node.querySelector('.children-wrapper');
    const icon = button.querySelector('.toggle-icon');
    
    if (childrenWrapper) {
        childrenWrapper.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }
}
</script>
@endpush
@endif