<div class="tree-node">
    <div class="node-content {{ strtolower($unit->type) }}" data-unit-id="{{ $unit->id }}">
        <h5>{{ $unit->name }}</h5>
        <div class="unit-code">{{ $unit->unit_code }}</div>
        <div class="unit-type">{{ $unit->type }}</div>
        
        @if($unit->positions->count() > 0)
            <div class="staff-count">
                <i class="fas fa-users"></i> 
                {{ $unit->positions->sum(function($position) {
                    return $position->employees->count();
                }) }} Staff in {{ $unit->positions->count() }} Position(s)
            </div>
        @endif

        @if($unit->positions->count() > 0 && $level < 3)
            <div class="positions-list">
                @foreach($unit->positions->take(3) as $position)
                    <div class="position-item">
                        <i class="fas fa-briefcase"></i> 
                        <strong>{{ $position->title }}</strong>
                        @if($position->employees->count() > 0)
                            <br>
                            <small class="ms-3">
                                @foreach($position->employees as $assignment)
                                    <i class="fas fa-user"></i> {{ $assignment->user->name ?? 'N/A' }}
                                @endforeach
                            </small>
                        @else
                            <br><small class="ms-3 text-muted">Vacant</small>
                        @endif
                    </div>
                @endforeach
                
                @if($unit->positions->count() > 3)
                    <div class="position-item text-muted">
                        <small>+ {{ $unit->positions->count() - 3 }} more position(s)</small>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($unit->children->count() > 0)
        <div class="children-container">
            @foreach($unit->children as $child)
                @include('org_units.partials.tree-node', ['unit' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>