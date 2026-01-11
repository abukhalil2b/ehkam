@php 
        $hasSubordinates = $position->subordinates->isNotEmpty();
    // Get attached unit name for display
    $unitName = $position->orgUnits->first()->name ?? 'غير مرتبط بوحدة';
@endphp

<div x-data="{ open: true }" class="mb-2 relative">
    <div class="flex items-center justify-between group p-2 rounded-lg hover:bg-white hover:shadow-sm border border-transparent hover:border-gray-200 transition-all duration-200"
        style="margin-right: {{ $depth * 24 }}px; border-right: 2px solid {{ $depth == 0 ? '#9333ea' : '#e5e7eb' }};">
        <!-- Custom indented line -->

        <div class="flex items-center gap-3 cursor-pointer flex-1" @click.stop="open = !open">
            <span class="text-gray-400">
                @if ($hasSubordinates)
                    <span class="material-icons transition-transform duration-200 text-sm"
                        :class="open ? 'rotate-90' : ''">chevron_left</span> {{-- Rtl chevron --}}
                @else
                    <span class="material-icons text-sm opacity-20">fiber_manual_record</span>
                @endif
            </span>

            <div class="flex flex-col">
                <span class="font-bold text-gray-800 text-base leading-snug">{{ $position->title }}</span>
                <span class="text-xs text-gray-500 flex items-center gap-1">
                    <span class="material-icons text-[10px]">domain</span> {{ $unitName }}
                    @if($position->job_code)
                        <span class="text-gray-300 mx-1">|</span> <span class="font-mono">{{ $position->job_code }}</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div
            class="flex items-center space-x-2 rtl:space-x-reverse opacity-0 group-hover:opacity-100 transition-opacity">
            <a href="{{ route('positions.edit', $position->id) }}"
                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-full transition" title="تعديل">
                <span class="material-icons text-base">edit</span>
            </a>

            <form action="{{ route('positions.destroy', $position->id) }}" method="POST"
                onsubmit="return confirm('هل أنت متأكد من حذف هذا المسمى الوظيفي؟')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-full transition" title="حذف">
                    <span class="material-icons text-base">delete</span>
                </button>
            </form>
        </div>
    </div>

    @if ($hasSubordinates)
        <div x-show="open" x-transition:enter="transition ease-out duration-100"
            x-transition:leave="transition ease-in duration-75" class="mt-1">
            @foreach ($position->subordinates as $sub)
                @include('positions.partials._hierarchy-item', [
                    'position' => $sub,
                    'depth' => $depth + 1
                ])
            @endforeach
            </div>
    @endif
</div>
