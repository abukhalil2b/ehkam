<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }} — شاشة عرض PESTLE التفاعلية</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    
    <!-- Confetti for celebrations -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
            overflow: hidden;
        }

        /* Animated mesh gradient background */
        .mesh-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            opacity: 0.03;
            animation: pulseMesh 10s ease-in-out infinite;
        }

        @keyframes pulseMesh {
            0%, 100% { transform: scale(1); opacity: 0.03; }
            50% { transform: scale(1.1); opacity: 0.05; }
        }

        /* Glassmorphism utilities */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-dark {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .no-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .no-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.8); }
            70% { transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .animate-slide-in {
            animation: slideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-pop {
            animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* New item highlight */
        .new-item {
            position: relative;
            overflow: hidden;
        }
        .new-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.1), transparent);
            animation: shimmer 2s;
        }

        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 100px;
            left: 20px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border-right: 4px solid;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
            pointer-events: all;
            max-width: 300px;
        }

        /* Card hover effects */
        .pestle-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pestle-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
        }

        /* Counter animation */
        .counter-pulse {
            animation: popIn 0.3s ease-out;
        }

        /* Presentation display mode - larger fonts, high contrast for TV/projector */
        body.display-mode .display-mode-hide { display: none !important; }
        body.display-mode .pestle-section-title { font-size: 1.25rem; }
        body.display-mode .pestle-section-subtitle { font-size: 0.875rem; }
        body.display-mode .pestle-item-content { font-size: 1.125rem; }
        body.display-mode .pestle-item-meta { font-size: 0.875rem; }
        body.display-mode .pestle-item-time { font-size: 0.8125rem; }
        body.display-mode main .pestle-card { min-height: min(420px, 40vh); }

        /* Sticky-note style for participant cards */
        .sticky-note {
            box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.04);
            border-left-width: 4px;
        }

        /* Floating QR button (visible only in display mode when sidebar hidden) */
        .qr-float-btn {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 40;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
            display: none;
            align-items: center;
            justify-content: center;
        }
        body.display-mode .qr-float-btn { display: flex; }
        body.display-mode .qr-float-btn:hover { transform: scale(1.05); }

        /* QR modal overlay */
        .qr-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .qr-modal-overlay.show { display: flex; }
        .qr-modal-content {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            max-width: 320px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }

        /* Sidebar collapse: hide sidebar and let main take full width on xl */
        @media (min-width: 1280px) {
            #mainGrid #pestleSidebar.collapsed { display: none !important; }
            #mainGrid #pestleMainSection.sidebar-collapsed { grid-column: 1 / -1; }
        }
        @media (max-width: 1279px) {
            #pestleSidebar.collapsed { display: none !important; }
        }

        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            body { overflow: visible; background: white; }
            .pestle-card { break-inside: avoid; page-break-inside: avoid; }
            .qr-float-btn, .qr-modal-overlay { display: none !important; }
        }
    </style>
</head>

<body class="text-gray-800 antialiased min-h-screen flex flex-col">

    <div class="mesh-bg"></div>

    <!-- Toast Container for Notifications -->
    <div class="toast-container" id="toastContainer"></div>

    <header class="glass border-b border-gray-200/50 px-6 py-4 flex items-center justify-between shrink-0 z-20 shadow-sm sticky top-0">
        <div class="flex items-center gap-4">
            <div class="bg-gradient-to-br from-indigo-600 to-violet-600 text-white p-3 rounded-xl shadow-lg shadow-indigo-200 animate-float">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    {{ $project->title }}
                </h1>
                <p class="text-gray-500 text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    التحليل الاستراتيجي التفاعلي المباشر
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Presentation display mode toggle -->
            <button type="button" onclick="toggleDisplayMode()" id="displayModeBtn" class="no-print glass px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-white transition-all flex items-center gap-2" title="وضع العرض التقديمي">
                <svg id="displayModeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <span id="displayModeText">وضع العرض</span>
            </button>

            <!-- Sidebar (QR) toggle -->
            <button type="button" onclick="toggleSidebar()" id="sidebarToggle" class="no-print display-mode-hide glass px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-white transition-all flex items-center gap-2" title="إظهار/إخفاء QR">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span id="sidebarToggleText">إخفاء QR</span>
            </button>

            <!-- Header actions (hidden in display mode) -->
            <div id="headerActions" class="display-mode-hide flex items-center gap-3">
                <button onclick="toggleSound()" id="soundBtn" class="no-print glass px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-white transition-all flex items-center gap-2">
                    <svg id="soundIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                    </svg>
                    <span id="soundText">الصوت مفعل</span>
                </button>
                <button onclick="window.print()" class="no-print glass px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-white transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    طباعة
                </button>
            </div>

            <!-- Live Indicator -->
            <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-200">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-bold text-green-700">مباشر</span>
            </div>
        </div>
    </header>

    <!-- Floating QR button (shown in display mode when sidebar is hidden) -->
    <button type="button" onclick="openQrModal()" class="qr-float-btn no-print" id="qrFloatBtn" title="عرض QR ورابط المشاركة" aria-label="عرض QR">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
    </button>

    <!-- QR Modal (for display mode) -->
    <div class="qr-modal-overlay no-print" id="qrModal" onclick="closeQrModal(event)">
        <div class="qr-modal-content" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">شارك الآن</h3>
                <button type="button" onclick="closeQrModal()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500" aria-label="إغلاق">&times;</button>
            </div>
            <div class="bg-gray-100 p-2 rounded-lg mx-auto mb-4 w-[200px] h-[200px] flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                {!! $qrCode !!}
            </div>
            <div class="bg-gray-100 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600">رابط المشاركة</p>
                    <button onclick="copyUrl(); closeQrModal();" class="text-xs bg-indigo-600 text-white hover:bg-indigo-700 px-2 py-1 rounded transition-colors flex items-center gap-1" id="copyBtnModal">
                        <span id="copyTextModal">نسخ</span>
                    </button>
                </div>
                <p class="font-mono text-xs text-gray-800 break-all dir-ltr select-all">{{ $publicUrl }}</p>
            </div>
        </div>
    </div>

    <main class="flex-1 p-4 md:p-6 pb-20 overflow-auto">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 max-w-[1920px] mx-auto items-start" id="mainGrid">
            
            <!-- Sidebar (Sticky, collapsible) -->
            <aside id="pestleSidebar" class="xl:col-span-3 flex flex-col gap-4 sticky top-24 transition-all">
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-xl shadow-lg p-5 text-white shrink-0">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            شارك الآن
                        </h2>
                        <span class="bg-white/20 px-2 py-1 rounded text-xs animate-pulse">Live</span>
                    </div>

                    <div class="bg-white p-2 rounded-lg shadow-inner mx-auto mb-4 w-[180px] h-[180px] flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                        {!! $qrCode !!}
                    </div>

                    <div class="bg-black/20 rounded-lg p-3 backdrop-blur-sm border border-white/10">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs text-indigo-100">رابط المشاركة</p>
                            <button onclick="copyUrl()" class="text-xs bg-white/10 hover:bg-white/20 px-2 py-1 rounded transition-colors flex items-center gap-1" id="copyBtn">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <span id="copyText">نسخ</span>
                            </button>
                        </div>
                        <p class="font-mono text-xs text-white/90 break-all dir-ltr select-all">{{ $publicUrl }}</p>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-indigo-50/50 backdrop-blur rounded-xl shadow-sm border border-indigo-100 p-5">
                    <p class="text-xs text-indigo-500 font-bold mb-1 uppercase tracking-wider">إجمالي المشاركات</p>
                    <p id="total-count" class="text-5xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 bg-clip-text text-transparent tracking-tight">
                        {{ $project->items->count() }}
                    </p>
                </div>
            </aside>

            <!-- Main Grid: 6 columns on xl (Miro-style), 2 on md, 1 on small -->
            <section class="xl:col-span-9" id="pestleMainSection">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

                    @php
                        $sections = [
                            'political' => [
                                'title' => 'السياسية', 
                                'subtitle' => 'Political',
                                'color' => 'rose',
                                'gradient' => 'from-rose-500 to-pink-600',
                                'shadow' => 'shadow-rose-200',
                                'light_bg' => 'bg-rose-50',
                                'border' => 'border-rose-500',
                                'text' => 'text-rose-600',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'
                            ],
                            'economic' => [
                                'title' => 'الاقتصادية', 
                                'subtitle' => 'Economic',
                                'color' => 'blue',
                                'gradient' => 'from-blue-600 to-cyan-500',
                                'shadow' => 'shadow-blue-200',
                                'light_bg' => 'bg-blue-50',
                                'border' => 'border-blue-500',
                                'text' => 'text-blue-600',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                            ],
                            'social' => [
                                'title' => 'الاجتماعية', 
                                'subtitle' => 'Social',
                                'color' => 'amber',
                                'gradient' => 'from-amber-500 to-orange-500',
                                'shadow' => 'shadow-amber-200',
                                'light_bg' => 'bg-amber-50',
                                'border' => 'border-amber-500',
                                'text' => 'text-amber-600',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'
                            ],
                            'technological' => [
                                'title' => 'التقنية', 
                                'subtitle' => 'Technological',
                                'color' => 'violet',
                                'gradient' => 'from-violet-600 to-fuchsia-500',
                                'shadow' => 'shadow-violet-200',
                                'light_bg' => 'bg-violet-50',
                                'border' => 'border-violet-500',
                                'text' => 'text-violet-600',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'
                            ],
                            'legal' => [
                                'title' => 'القانونية', 
                                'subtitle' => 'Legal',
                                'color' => 'slate',
                                'gradient' => 'from-slate-700 to-gray-600',
                                'shadow' => 'shadow-slate-200',
                                'light_bg' => 'bg-slate-50',
                                'border' => 'border-slate-600',
                                'text' => 'text-slate-700',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>'
                            ],
                            'environmental' => [
                                'title' => 'البيئية', 
                                'subtitle' => 'Environmental',
                                'color' => 'emerald',
                                'gradient' => 'from-emerald-500 to-teal-500',
                                'shadow' => 'shadow-emerald-200',
                                'light_bg' => 'bg-emerald-50',
                                'border' => 'border-emerald-500',
                                'text' => 'text-emerald-600',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'
                            ],
                        ];
                    @endphp

                    @foreach($sections as $type => $meta)
                        @php 
                            $count = $project->items->where('type', $type)->count();
                            $items = $project->items->where('type', $type)->sortByDesc('created_at');
                        @endphp

                        <div class="pestle-card flex flex-col min-h-[420px] bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex-1" data-type="{{ $type }}">
                            <!-- Section header with gradient (larger titles) -->
                            <div class="p-4 border-b border-gray-100 bg-gradient-to-r {{ $meta['light_bg'] }}/50 to-white border-t-4 {{ $meta['border'] }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $meta['gradient'] }} text-white flex items-center justify-center shadow-lg {{ $meta['shadow'] }}">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                {!! $meta['icon'] !!}
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="pestle-section-title font-bold text-gray-800 text-lg">{{ $meta['title'] }}</h3>
                                            <span class="pestle-section-subtitle text-xs text-gray-500 font-medium">{{ $meta['subtitle'] }}</span>
                                        </div>
                                    </div>
                                    <span id="count-{{ $type }}" class="px-3 py-1 rounded-full bg-white text-sm font-bold shadow-sm border border-{{ $meta['color'] }}-100 text-{{ $meta['color'] }}-600 min-w-[2rem] text-center transition-all">
                                        {{ $count }}
                                    </span>
                                </div>
                            </div>

                            <!-- List (sticky-note style items, larger content text) -->
                            <div id="list-{{ $type }}" class="flex-1 overflow-y-auto no-scrollbar p-3 space-y-2 bg-gray-50/30 min-h-0">
                                @forelse($items as $item)
                                    <div class="sticky-note bg-white p-4 rounded-xl border border-gray-100 border-s-4 {{ $meta['border'] }} hover:shadow-md transition-all group animate-fade-in" data-id="{{ $item->id }}">
                                        <p class="pestle-item-content text-gray-800 font-medium text-base leading-relaxed whitespace-pre-wrap">{{ $item->content }}</p>
                                        
                                        <div class="pestle-item-meta flex justify-between items-center mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xs text-gray-600 font-bold border border-gray-200">
                                                    {{ mb_substr($item->participant_name, 0, 1) }}
                                                </div>
                                                <p class="text-xs text-gray-600 font-medium">
                                                    {{ $item->participant_name }}
                                                </p>
                                            </div>
                                            <time class="pestle-item-time text-[11px] text-gray-500 font-mono group-hover:text-{{ $meta['color'] }}-500 transition-colors" datetime="{{ $item->created_at }}">
                                                {{ $item->created_at->format('H:i') }}
                                            </time>
                                        </div>
                                    </div>
                                @empty
                                    <div class="h-full flex flex-col items-center justify-center text-gray-400 py-12 opacity-60 empty-state min-h-[200px]" data-type="{{ $type }}">
                                        <div class="w-16 h-16 rounded-2xl bg-{{ $meta['color'] }}-50 flex items-center justify-center mb-3 animate-pulse">
                                            <svg class="w-8 h-8 text-{{ $meta['color'] }}-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium">بانتظار المشاركات...</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach

                </div>
            </section>
        </div>


    </main>

    <script>
        // State management
        let soundEnabled = true;
        let lastCounts = {};
        let totalItems = {{ $project->items->count() }};
        let isUpdating = false;
        const sections = ['political', 'economic', 'social', 'technological', 'legal', 'environmental'];
        
        // Initialize counts
        sections.forEach(type => {
            lastCounts[type] = parseInt(document.getElementById(`count-${type}`).innerText) || 0;
        });

        // Sound toggle
        function toggleSound() {
            soundEnabled = !soundEnabled;
            const btn = document.getElementById('soundBtn');
            const icon = document.getElementById('soundIcon');
            const text = document.getElementById('soundText');
            
            if (soundEnabled) {
                btn.classList.remove('opacity-50');
                text.textContent = 'الصوت مفعل';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>';
            } else {
                btn.classList.add('opacity-50');
                text.textContent = 'الصوت mute';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>';
            }
        }

        // Play notification sound
        function playSound() {
            if (!soundEnabled) return;
            
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime); // C5
                oscillator.frequency.exponentialRampToValueAtTime(659.25, audioContext.currentTime + 0.1); // E5
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.3);
            } catch (e) {
                console.log('Audio not supported');
            }
        }

        // Confetti celebration
        function celebrate(type) {
            const colors = {
                political: ['#f43f5e', '#ec4899'],
                economic: ['#3b82f6', '#06b6d4'],
                social: ['#f59e0b', '#f97316'],
                technological: ['#8b5cf6', '#a855f7'],
                legal: ['#64748b', '#475569'],
                environmental: ['#10b981', '#14b8a6']
            };

            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: colors[type] || ['#6366f1', '#8b5cf6'],
                disableForReducedMotion: true
            });
        }

        // Show toast notification
        function showToast(type, count) {
            const titles = {
                political: 'السياسية',
                economic: 'الاقتصادية',
                social: 'الاجتماعية',
                technological: 'التقنية',
                legal: 'القانونية',
                environmental: 'البيئية'
            };

            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast border-${type}-500 animate-slide-in`;
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-${type}-100 text-${type}-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">مشاركة جديدة</p>
                    <p class="text-xs text-gray-500">تمت الإضافة إلى ${titles[type]}</p>
                </div>
            `;
            
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Copy URL function (updates both sidebar and modal buttons)
        function copyUrl() {
            const url = '{{ $publicUrl }}';
            const text = document.getElementById('copyText');
            const textModal = document.getElementById('copyTextModal');
            const btn = document.getElementById('copyBtn');
            
            navigator.clipboard.writeText(url).then(() => {
                if (btn) {
                    btn.classList.add('bg-green-500', 'text-white');
                    btn.classList.remove('bg-white/20');
                }
                if (text) text.textContent = 'تم النسخ!';
                if (textModal) textModal.textContent = 'تم النسخ!';
                
                setTimeout(() => {
                    if (btn) {
                        btn.classList.remove('bg-green-500', 'text-white');
                        btn.classList.add('bg-white/20');
                    }
                    if (text) text.textContent = 'نسخ';
                    if (textModal) textModal.textContent = 'نسخ';
                }, 2000);
            });
        }

        // Presentation display mode: toggle body class, hide sidebar/header actions, show floating QR
        function toggleDisplayMode() {
            document.body.classList.toggle('display-mode');
            const isDisplay = document.body.classList.contains('display-mode');
            const sidebar = document.getElementById('pestleSidebar');
            const mainSection = document.getElementById('pestleMainSection');
            const btn = document.getElementById('displayModeBtn');
            const text = document.getElementById('displayModeText');
            const icon = document.getElementById('displayModeIcon');
            
            if (isDisplay) {
                sidebar.classList.add('collapsed');
                mainSection.classList.add('sidebar-collapsed');
                mainSection.classList.remove('xl:col-span-9');
                mainSection.classList.add('xl:col-span-12');
                if (text) text.textContent = 'خروج من وضع العرض';
                if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                try { window.history.replaceState({}, '', window.location.pathname + '?display=1'); } catch (e) {}
            } else {
                sidebar.classList.remove('collapsed');
                mainSection.classList.remove('sidebar-collapsed');
                mainSection.classList.remove('xl:col-span-12');
                mainSection.classList.add('xl:col-span-9');
                if (text) text.textContent = 'وضع العرض';
                if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>';
                try { window.history.replaceState({}, '', window.location.pathname); } catch (e) {}
            }
        }

        // Sidebar (QR) collapse: hide sidebar, expand main
        function toggleSidebar() {
            const sidebar = document.getElementById('pestleSidebar');
            const mainSection = document.getElementById('pestleMainSection');
            const text = document.getElementById('sidebarToggleText');
            const isCollapsed = sidebar.classList.toggle('collapsed');
            if (isCollapsed) {
                mainSection.classList.add('sidebar-collapsed');
                mainSection.classList.remove('xl:col-span-9');
                mainSection.classList.add('xl:col-span-12');
                if (text) text.textContent = 'إظهار QR';
            } else {
                mainSection.classList.remove('sidebar-collapsed');
                mainSection.classList.remove('xl:col-span-12');
                mainSection.classList.add('xl:col-span-9');
                if (text) text.textContent = 'إخفاء QR';
            }
        }

        function openQrModal() {
            document.getElementById('qrModal').classList.add('show');
        }
        function closeQrModal(e) {
            if (e && e.target !== document.getElementById('qrModal')) return;
            document.getElementById('qrModal').classList.remove('show');
        }

        // Border class per PESTLE type (for sticky-note and createItemHTML)
        const typeBorderClass = {
            political: 'border-rose-500',
            economic: 'border-blue-500',
            social: 'border-amber-500',
            technological: 'border-violet-500',
            legal: 'border-slate-600',
            environmental: 'border-emerald-500'
        };

        // Create HTML for new item (sticky-note style, larger text)
        function createItemHTML(item, type) {
            const date = new Date(item.created_at);
            const time = date.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
            const initial = item.participant_name ? item.participant_name.charAt(0) : '?';
            const borderClass = typeBorderClass[type] || 'border-indigo-500';
            return `
                <div class="sticky-note bg-white p-4 rounded-xl border border-gray-100 border-s-4 ${borderClass} hover:shadow-md transition-all group new-item animate-pop" data-id="${item.id}">
                    <p class="pestle-item-content text-gray-800 font-medium text-base leading-relaxed whitespace-pre-wrap">${item.content}</p>
                    <div class="pestle-item-meta flex justify-between items-center mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xs text-gray-600 font-bold border border-gray-200">
                                ${initial}
                            </div>
                            <p class="text-xs text-gray-600 font-medium">${item.participant_name}</p>
                        </div>
                        <time class="pestle-item-time text-[11px] text-gray-500 font-mono transition-colors">${time}</time>
                    </div>
                </div>
            `;
        }

        // Smart update function - only adds new items instead of replacing all
        async function fetchUpdates() {
            if (isUpdating) return;
            isUpdating = true;

            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                let hasNewContent = false;
                let newTotal = 0;

                // Check each section
                sections.forEach(type => {
                    const listEl = document.getElementById(`list-${type}`);
                    const countEl = document.getElementById(`count-${type}`);
                    const newListEl = doc.getElementById(`list-${type}`);
                    const newCountEl = doc.getElementById(`count-${type}`);
                    
                    if (!newListEl || !newCountEl) return;
                    
                    const newCount = parseInt(newCountEl.innerText) || 0;
                    const currentCount = parseInt(countEl.innerText) || 0;
                    
                    if (newCount > currentCount) {
                        hasNewContent = true;
                        
                        // Update count with animation
                        countEl.innerText = newCount;
                        countEl.classList.add('counter-pulse', 'bg-green-100', 'text-green-700', 'scale-110');
                        setTimeout(() => {
                            countEl.classList.remove('counter-pulse', 'bg-green-100', 'text-green-700', 'scale-110');
                        }, 500);
                        
                        // Extract new items
                        const currentIds = Array.from(listEl.querySelectorAll('[data-id]')).map(el => el.dataset.id);
                        const newItems = Array.from(newListEl.querySelectorAll('[data-id]')).filter(el => !currentIds.includes(el.dataset.id));
                        
                        // Remove empty state if exists
                        const emptyState = listEl.querySelector('.empty-state');
                        if (emptyState) emptyState.remove();
                        
                        // Add new items to top
                        newItems.reverse().forEach(itemEl => {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = createItemHTML({
                                id: itemEl.dataset.id,
                                content: itemEl.querySelector('p').innerText,
                                participant_name: itemEl.querySelector('.text-xs.font-medium').innerText,
                                created_at: new Date().toISOString()
                            }, type);
                            
                            const newEl = tempDiv.firstElementChild;
                            listEl.insertBefore(newEl, listEl.firstChild);
                            
                            // Auto-scroll to new item if user is at top
                            if (listEl.scrollTop < 100) {
                                newEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        });
                        
                        showToast(type, newCount);
                        celebrate(type);
                        playSound();
                    }
                    
                    newTotal += newCount;
                });

                // Update total
                if (hasNewContent) {
                    const totalEl = document.getElementById('total-count');
                    const oldTotal = parseInt(totalEl.innerText);
                    totalEl.innerText = newTotal;
                    totalEl.classList.add('counter-pulse');
                    setTimeout(() => totalEl.classList.remove('counter-pulse'), 500);
                    
                    // Milestone celebration every 10 items
                    if (Math.floor(newTotal / 10) > Math.floor(oldTotal / 10)) {
                        setTimeout(() => {
                            confetti({
                                particleCount: 200,
                                spread: 100,
                                origin: { y: 0.6 },
                                colors: ['#6366f1', '#8b5cf6', '#ec4899', '#10b981']
                            });
                            
                            const badge = document.getElementById('milestone-badge');
                            badge.classList.remove('opacity-0', 'scale-0');
                            setTimeout(() => {
                                badge.classList.add('opacity-0', 'scale-0');
                            }, 3000);
                        }, 500);
                    }
                }

            } catch (error) {
                console.error('Error fetching updates:', error);
            } finally {
                isUpdating = false;
            }
        }

        // Visibility API - pause polling when tab is hidden
        let pollInterval;
        
        function startPolling() {
            pollInterval = setInterval(fetchUpdates, 3000);
        }
        
        function stopPolling() {
            clearInterval(pollInterval);
        }
        
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopPolling();
            } else {
                fetchUpdates(); // Immediate check when returning
                startPolling();
            }
        });
        
        // Start polling
        startPolling();

        // Apply ?display=1 from URL on load
        if (window.location.search.indexOf('display=1') !== -1 && !document.body.classList.contains('display-mode')) {
            toggleDisplayMode();
        }

        // Manual refresh on click for mobile
        document.addEventListener('dblclick', () => {
            fetchUpdates();
        });
    </script>
</body>
</html>