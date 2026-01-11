@php
    // Map Laravel session keys to UI configurations
    $flashConfig = [
        'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-500', 'text' => 'text-emerald-800', 'icon' => 'check_circle', 'title' => 'تمت العملية بنجاح'],
        'error'   => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'text' => 'text-red-800', 'icon' => 'error', 'title' => 'حدث خطأ'],
        'warning' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-500', 'text' => 'text-amber-800', 'icon' => 'warning', 'title' => 'تنبيه'],
        'info'    => ['bg' => 'bg-blue-50', 'border' => 'border-blue-500', 'text' => 'text-blue-800', 'icon' => 'info', 'title' => 'ملاحظة'],
    ];

    // Determine the active message
    $activeType = null;
    $message = null;

    foreach ($flashConfig as $key => $config) {
        if (session()->has($key)) {
            $activeType = $key;
            $message = session($key);
            break;
        }
    }
    
    // Fallback for 'status' or generic 'message'
    if (!$activeType && session('status')) {
        $activeType = 'success';
        $message = session('status');
    }
    
    $config = $activeType ? $flashConfig[$activeType] : null;
@endphp

{{-- 1. Floating Toast Notification (Success, Warning, Info) --}}
@if($config)
<div x-data="{ show: true, progress: 100 }"
     x-init="setTimeout(() => show = false, 5000); setInterval(() => progress -= 2, 100)"
     x-show="show"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-24 left-4 z-50 w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg border-r-4 {{ $config['border'] }}"
     role="alert">
    
    <div class="p-4 flex items-start gap-4">
        <div class="flex-shrink-0">
            <span class="material-icons text-2xl {{ $config['text'] }}">
                {{ $config['icon'] }}
            </span>
        </div>
        <div class="w-0 flex-1 pt-0.5">
            <p class="text-sm font-bold text-gray-900">{{ $config['title'] }}</p>
            <p class="mt-1 text-sm text-gray-600 leading-relaxed">{{ $message }}</p>
        </div>
        <div class="flex-shrink-0 flex">
            <button @click="show = false" class="text-gray-400 hover:text-gray-500 transition">
                <span class="material-icons text-sm">close</span>
            </button>
        </div>
    </div>
    
    {{-- Progress Bar --}}
    <div class="h-1 w-full bg-gray-100">
        <div class="h-full {{ $config['text'] }} opacity-20 transition-all duration-100 linear" 
             :style="`width: ${progress}%`"></div>
    </div>
</div>
@endif

{{-- 2. Validation Errors Block (Static, keeps layout shift to draw attention) --}}
@if ($errors->any())
<div x-data="{ show: true }" x-show="show" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm" role="alert">
    <div class="flex items-start gap-3">
        <span class="material-icons text-red-600 mt-0.5">report_problem</span>
        <div class="flex-1">
            <h3 class="text-sm font-bold text-red-800 mb-1">يوجد {{ $errors->count() }} أخطاء في المدخلات:</h3>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
            <span class="material-icons text-lg">close</span>
        </button>
    </div>
</div>
@endif