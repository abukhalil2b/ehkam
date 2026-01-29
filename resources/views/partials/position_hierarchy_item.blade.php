@php
    // حساب عدد الموظفين الذين يشغلون هذا المسمى وظيفي حالياً
    $staffCount = $users->filter(function ($user) use ($position) {
        $currentHistory = $user->positionHistory->first(fn($h) => is_null($h->end_date));
        return $currentHistory && $currentHistory->position_id === $position->id;
    })->count();
@endphp

<div class="flex items-center space-x-2 text-right rtl:space-x-reverse">
    <span class="material-icons text-lg text-purple-600">person_pin</span>
    <span class="font-bold">{{ $position->title }}</span>
    
    @if ($staffCount > 0)
        <span class="ml-2 text-xs text-gray-500">({{ $staffCount }})</span>
    @endif
</div>