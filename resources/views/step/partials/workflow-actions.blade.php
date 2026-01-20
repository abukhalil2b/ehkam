@props(['step'])

{{-- Steps do not have workflow functionality --}}
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
    <h3 class="text-xl font-bold text-blue-900 mb-2 flex items-center gap-2">
        <span class="material-icons">info</span>
        معلومات الخطوة
    </h3>
    <p class="text-blue-800">
        الخطوات لا تستخدم نظام سير العمل. تتم متابعة الخطوات من خلال المراحل ({{ $step->phase }}) فقط.
    </p>
    @if($step->activity)
        <p class="text-blue-700 mt-2 text-sm">
            <strong>النشاط:</strong> {{ $step->activity->title }}
        </p>
    @endif
</div>