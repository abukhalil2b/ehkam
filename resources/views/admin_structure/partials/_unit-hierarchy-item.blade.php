@php
    // حساب عدد الموظفين في هذه الوحدة
    $staffCount = $users->filter(function ($user) use ($unit) {
        $currentHistory = $user->positionHistory->first(fn($h) => is_null($h->end_date));
        return $currentHistory && $currentHistory->organizational_unit_id === $unit->id;
    })->count();
@endphp

<div class="flex items-center space-x-2 text-right rtl:space-x-reverse" style="padding-right: {{ $depth * 20 }}px; padding-left: 0px;">
    <span class="material-icons text-lg text-blue-600">
        {{ $unit->type == 'Directorate' ? 'apartment' : 'category' }}
    </span>
    <span class="font-bold">[{{ $unit->type }}] </span>
    <span>{{ $unit->name }}</span>
    
    @if ($staffCount > 0)
        <span class="ml-2 text-xs text-gray-500">({{ $staffCount }})</span>
    @endif
</div>

@if ($staffCount > 0)
    <div class="text-xs text-gray-500 mt-1 mb-2 rtl:mr-{{ $depth * 5 }}">({{ $staffCount }} موظف معين)</div>
@endif

{{-- التكرار الذاتي: لكل ابن، نستدعي نفس الجزئية بزيادة العمق --}}
@foreach ($unit->children as $child)
    @include('admin_structure.partials._unit-hierarchy-item', ['unit' => $child, 'users' => $users, 'depth' => $depth + 1])
@endforeach
