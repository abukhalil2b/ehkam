@php $hasSubordinates = $position->subordinates->isNotEmpty(); @endphp

<div x-data="{ open: true }" class="mb-1">
    <div class="flex items-center gap-2 cursor-pointer py-1"
         style="padding-right: {{ $depth * 20 }}px;"
         @click.stop="open = !open">

        @if ($hasSubordinates)
            <span class="material-icons transition-transform duration-200" :class="open ? 'rotate-90' : ''">chevron_right</span>
        @else
            <span class="w-4 inline-block"></span>
        @endif

        <span class="font-semibold">{{ $position->title }}</span>
    </div>

    @if ($hasSubordinates)
        <div x-show="open" x-transition class="mt-1">
            @foreach ($position->subordinates as $sub)
                @include('admin_structure.partials._position-hierarchy-item', [
                    'position' => $sub,
                    'depth' => $depth + 1
                ])
            @endforeach
        </div>
    @endif
</div>
