<div 
    x-data="toastHandler()" 
    x-show="visible" 
    x-transition 
    class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none"
    style="display: none;"
>
    <div 
        class="pointer-events-auto px-6 py-3 rounded-lg text-white shadow-lg"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-yellow-500': type === 'warning',
            'bg-blue-600': type === 'info'
        }"
    >
        <span x-text="message"></span>
    </div>
</div>