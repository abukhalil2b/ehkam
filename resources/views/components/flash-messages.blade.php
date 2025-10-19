@php
    // Define the map of session keys to colors and icons
    $flashes = [
        'success' => [
            'bg' => 'bg-green-100',
            'border' => 'border-green-400',
            'text' => 'text-green-700',
            'icon' => 'check_circle',
        ],
        'error' => ['bg' => 'bg-red-100', 'border' => 'border-red-400', 'text' => 'text-red-700', 'icon' => 'error'],
        'warning' => [
            'bg' => 'bg-yellow-100',
            'border' => 'border-yellow-400',
            'text' => 'text-yellow-700',
            'icon' => 'warning',
        ],
        'info' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-400', 'text' => 'text-blue-700', 'icon' => 'info'],
    ];

    // Find the first available flash message
    $activeFlash = null;
    $messageKey = null;

    foreach (array_keys($flashes) as $key) {
        if (session($key) || session('status') === $key) {
            $activeFlash = $flashes[$key];
            $messageKey = $key;
            break;
        }
    }

    // Handle the generic 'message' key if no status-specific key was found
    if (!$activeFlash && session('message')) {
        $status = session('status', 'success');
        $activeFlash = $flashes[$status] ?? $flashes['info']; // Default to info if status is unknown
        $messageKey = 'message';
    }

    // Get the actual message content
    $message = $messageKey ? session($messageKey) : null;
@endphp

{{-- 1. Check for Flash Messages (Success/Error/Info) --}}
@if ($activeFlash)
    <div x-data="{ show: true }" x-show="show" x-transition:leave.duration.500ms x-init="setTimeout(() => show = false, 5000)" role="alert"
        class="mb-6 p-4 rounded-lg border-2 rtl:border-r-4 shadow-md text-sm cursor-pointer transition duration-300 hover:opacity-90 {{ $activeFlash['bg'] }} {{ $activeFlash['border'] }} {{ $activeFlash['text'] }}"
        @click="show = false">
        <div class="flex items-center gap-3">
            <span class="material-icons text-xl">
                {{ $activeFlash['icon'] }}
            </span>
            <span class="font-medium block sm:inline">{{ $message }}</span>
        </div>
    </div>
@endif

{{-- 2. Check for Validation Errors (Separate Block) --}}
@if ($errors->any())
    <div class="mb-6 p-4 rounded-lg border-2 rtl:border-r-4 shadow-md text-sm **bg-red-100 border-red-400 text-red-700**"
        role="alert">
        <div class="flex items-start gap-3">
            <span class="material-icons text-xl mt-0.5">
                error
            </span>
            <ul class="list-disc list-inside space-y-1">
                <p class="font-bold mb-1">الرجاء تصحيح الأخطاء التالية:</p>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
