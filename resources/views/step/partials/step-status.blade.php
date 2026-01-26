@php
    $instance = $step->workflowInstance;
    $workflowStatus = $instance?->status ?? 'draft';
@endphp

<span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full
    @if($workflowStatus === 'completed') bg-green-100 text-green-800
    @elseif($workflowStatus === 'rejected') bg-red-100 text-red-800
    @elseif($workflowStatus === 'in_progress') bg-blue-100 text-blue-800
    @elseif($workflowStatus === 'returned') bg-yellow-100 text-yellow-800
    @else bg-gray-100 text-gray-800
    @endif">
    @if($workflowStatus === 'completed')
        <span class="material-icons text-xs ml-1">check_circle</span> مكتمل
    @elseif($workflowStatus === 'rejected')
        <span class="material-icons text-xs ml-1">cancel</span> مرفوض
    @elseif($workflowStatus === 'in_progress')
        <span class="material-icons text-xs ml-1">pending</span> قيد المراجعة
    @elseif($workflowStatus === 'returned')
        <span class="material-icons text-xs ml-1">undo</span> معاد للتعديل
    @else
        <span class="material-icons text-xs ml-1">edit</span> مسودة
    @endif
</span>
