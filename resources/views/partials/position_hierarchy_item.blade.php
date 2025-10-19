@php
    // حساب عدد الموظفين الذين يشغلون هذا المسمى وظيفي حالياً
    $staffCount = $users->filter(function ($user) use ($position) {
        $currentHistory = $user->positionHistory->first(fn($h) => is_null($h->end_date));
        return $currentHistory && $currentHistory->position_id === $position->id;
    })->count();
@endphp

<div class="flex items-center space-x-2 text-right rtl:space-x-reverse" style="padding-right: {{ $depth * 20 }}px; padding-left: 0px;">
    <span class="material-icons text-lg text-purple-600">person_pin</span>
    <span class="font-bold">{{ $position->title }}</span>
    
    @if ($staffCount > 0)
        <span class="ml-2 text-xs text-gray-500">({{ $staffCount }})</span>
    @endif
</div>

{{-- التكرار الذاتي: لكل مرؤوس، نستدعي نفس الجزئية بزيادة العمق --}}
@foreach ($position->subordinates as $subordinate)
    @include('partials.position_hierarchy_item', ['position' => $subordinate, 'users' => $users, 'depth' => $depth + 1])
@endforeach