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

<table class="org-chart-table mx-auto" style="border-collapse: separate; border-spacing: 0;">
    <tr>
        <td class="p-2 align-top">
            <!-- Node Card -->
            <div class="node-box relative {{ $colorClass }} border-2 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-4 mx-auto"
                 style="min-width: 240px; max-width: 280px;"
                 data-unit-id="{{ $unit->id }}">
                
                @if($unit->children->count() > 0)
                    <button type="button" 
                            class="toggle-btn absolute top-2 left-2 w-6 h-6 rounded-full bg-black/10 hover:bg-black/20 flex items-center justify-center transition-colors"
                            onclick="toggleNode(this)">
                        <i class="toggle-icon fas fa-minus text-xs"></i>
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
                    <div class="mt-3 pt-3 border-t {{ $unit->type === 'Department' ? 'border-yellow-600' : ($unit->type === 'Section' ? 'border-gray-300' : 'border-white/30') }} text-right space-y-2 max-h-48 overflow-y-auto">
                        @foreach($unit->positions->take(5) as $position)
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
                        
                        @if($unit->positions->count() > 5)
                            <div class="text-xs opacity-70 text-center pt-2 border-t border-white/20">
                                + {{ $unit->positions->count() - 5 }} وظيفة أخرى
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </td>
    </tr>

    @if($unit->children->count() > 0)
    <tr class="children-row">
        <td colspan="1" class="p-0 align-top">
            <!-- Vertical Line -->
            <div class="flex justify-center">
                <div class="w-0.5 bg-gray-400" style="height: 30px;"></div>
            </div>
            
            <!-- Children Table -->
            <table class="w-full" style="border-collapse: separate; border-spacing: 20px 0;">
                <tr>
                    @foreach($unit->children as $child)
                    <td class="align-top relative" style="width: {{ 100 / $unit->children->count() }}%;">
                        <!-- Vertical Line to Child -->
                        <div class="flex justify-center mb-4">
                            <div class="w-0.5 bg-gray-400" style="height: 30px;"></div>
                        </div>
                        
                        @include('org_units.partials.tree-node-stable', ['unit' => $child, 'level' => $level + 1])
                    </td>
                    @endforeach
                </tr>
            </table>
        </td>
    </tr>
    @endif
</table>

@if($level === 0)
@push('styles')
<style>
    .org-chart-table {
        margin: 0 auto;
    }
    
    .node-box {
        display: inline-block;
    }

    /* Print styles */
    @media print {
        .toggle-btn {
            display: none !important;
        }
        .children-row {
            display: table-row !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function toggleNode(button) {
    const tr = button.closest('tr');
    const parentTable = tr.closest('table');
    const childrenRow = parentTable.querySelector('.children-row');
    const icon = button.querySelector('.toggle-icon');
    
    if (childrenRow) {
        if (childrenRow.style.display === 'none') {
            childrenRow.style.display = 'table-row';
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
        } else {
            childrenRow.style.display = 'none';
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
        }
    }
}

// Collapse all nodes at level 3 and below by default
document.addEventListener('DOMContentLoaded', function() {
    const allNodes = document.querySelectorAll('[data-unit-id]');
    allNodes.forEach((node, index) => {
        if (index > 10) { // Collapse after first 10 nodes
            const button = node.querySelector('.toggle-btn');
            if (button) {
                const tr = button.closest('tr');
                const parentTable = tr.closest('table');
                const childrenRow = parentTable.querySelector('.children-row');
                if (childrenRow) {
                    childrenRow.style.display = 'none';
                    const icon = button.querySelector('.toggle-icon');
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                }
            }
        }
    });
});
</script>
@endpush
@endif