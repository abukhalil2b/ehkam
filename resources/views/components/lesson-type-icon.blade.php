
@php
    $icons = [
        'video' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22.54 6.42a2.79 2.79 0 00-1.96-1.96C18.88 4 12 4 12 4s-6.88 0-8.58.46a2.79 2.79 0 00-1.96 1.96A29.94 29.94 0 001 12a29.94 29.94 0 00.46 5.58 2.79 2.79 0 001.96 1.96C5.12 20 12 20 12 20s6.88 0 8.58-.46a2.79 2.79 0 001.96-1.96A29.94 29.94 0 0023 12a29.94 29.94 0 00-.46-5.58z"></path><polygon fill="currentColor" points="10 15 15 12 10 9 10 15"></polygon></svg>',
        'live'  => '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"></path></svg>',
        'file'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-blue-600"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        'text'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-green-600"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>',
        'quiz'  => '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10a4 4 0 118 0c0 2-4 2-4 5m0 4h.01"></path></svg>',
    ];
@endphp

<div class="flex items-center gap-1" title="نوع الدرس: {{ __($type) }}">
    {!! $icons[$type] ?? $icons['text'] !!}
</div>
