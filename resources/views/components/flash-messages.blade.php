@php
    // === Flash Message Configuration ===
    $flashes = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-300',
            'text' => 'text-green-800',
            'icon' => 'check_circle',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-300',
            'text' => 'text-red-800',
            'icon' => 'error',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-300',
            'text' => 'text-yellow-800',
            'icon' => 'warning',
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-300',
            'text' => 'text-blue-800',
            'icon' => 'info',
        ],
    ];

    // === Determine Active Flash ===
    $activeFlash = null;
    $messageKey = null;

    foreach (array_keys($flashes) as $key) {
        if (session($key) || session('status') === $key) {
            $activeFlash = $flashes[$key];
            $messageKey = $key;
            break;
        }
    }

    if (!$activeFlash && session('message')) {
        $status = session('status', 'success');
        $activeFlash = $flashes[$status] ?? $flashes['info'];
        $messageKey = 'message';
    }

    $message = $messageKey ? session($messageKey) : null;
@endphp

{{-- === Flash Message === --}}
<div class="p-6">
    @if ($activeFlash)
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition.opacity.duration.400ms 
        x-init="setTimeout(() => show = false, 5000)"
        @click="show = false"
        role="alert"
        class="mb-6 p-4 rounded-lg border rtl:border-r-4 shadow-sm text-sm cursor-pointer transition duration-300 hover:opacity-90 {{ $activeFlash['bg'] }} {{ $activeFlash['border'] }} {{ $activeFlash['text'] }}"
    >
        <div class="flex items-start gap-3">
            <span class="material-icons text-2xl mt-0.5">
                {{ $activeFlash['icon'] }}
            </span>
            <div class="flex-1">
                <p class="font-semibold text-base mb-0.5">
                    @switch($messageKey)
                        @case('success') تم بنجاح @break
                        @case('error') حدث خطأ @break
                        @case('warning') تنبيه @break
                        @case('info') ملاحظة @break
                        @default إشعار
                    @endswitch
                </p>
                <p class="text-sm leading-relaxed">{{ $message }}</p>
            </div>
            <button @click="show = false" class="text-xl opacity-70 hover:opacity-100 transition">
                <span class="material-icons">close</span>
            </button>
        </div>
    </div>
@endif
</div>

{{-- === Validation Errors === --}}
@if ($errors->any())
    <div 
        role="alert" 
        class="mb-6 p-4 rounded-lg border rtl:border-r-4 shadow-sm text-sm bg-red-50 border-red-300 text-red-800"
    >
        <div class="flex items-start gap-3">
            <span class="material-icons text-2xl mt-0.5">error</span>
            <div>
                <p class="font-semibold text-base mb-1">الرجاء تصحيح الأخطاء التالية:</p>
                <ul class="list-disc list-inside space-y-1 text-sm leading-relaxed">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
