<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مخطط إيشيكاوا (عظم السمكة) - النسخة الاحترافية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            overflow: hidden;
        }

        ::-webkit-scrollbar { height: 8px; width: 8px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        .canvas-container {
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        input, textarea {
            background: #334155;
            border: 1px solid #475569;
            color: white;
            transition: all 0.2s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .list-item-anim { animation: slideIn 0.3s ease-out forwards; }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(10px, -10px) rotate(-1deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }
        .watermark-anim {
            animation: float 12s ease-in-out infinite;
        }

        .category-color-picker {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            cursor: pointer;
            border: 2px solid #475569;
            transition: all 0.2s;
        }
        .category-color-picker:hover {
            transform: scale(1.1);
            border-color: #38bdf8;
        }

        /* Action Plan Drawer */
        .action-drawer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0; /* Adjusted for full width minus sidebar is handled by flex */
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border-top: 1px solid #475569;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 40;
            height: 80vh;
            transform: translateY(100%);
            display: flex;
            flex-direction: column;
        }
        .action-drawer.open {
            transform: translateY(0);
        }
        .action-tab {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            background: #38bdf8;
            color: #0f172a;
            padding: 8px 24px;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 -4px 20px rgba(56, 189, 248, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 16px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .step-card h3 {
            color: #38bdf8;
            font-weight: bold;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body class="h-screen w-screen flex flex-col md:flex-row">

    <aside class="glass-panel w-full md:w-96 flex flex-col h-full z-20 shadow-2xl shrink-0 order-2 md:order-1 border-r-0 md:border-l-0 border-l-slate-700">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-l from-cyan-400 to-blue-500 flex items-center gap-2">
                <i data-lucide="activity"></i> مخطط عظم السمكة
            </h1>
            <p class="text-xs text-slate-400 mt-1">أداة تحليل الأسباب الجذرية (Ishikawa)</p>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="target" class="w-3 h-3"></i>
                    الأثر (المشكلة الرئيسية)
                </label>
                <textarea 
                    id="problemInput" 
                    class="w-full p-3 rounded text-sm font-medium resize-none focus:ring-2 focus:ring-cyan-500" 
                    rows="2"
                    placeholder="اكتب المشكلة أو الأثر هنا..."
                ></textarea>
            </div>

            <hr class="border-slate-700">

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="layers" class="w-3 h-3"></i>
                        الفئات (الأسباب)
                    </label>
                    <button onclick="addCategory()" class="p-1.5 hover:bg-slate-700 rounded text-cyan-400 transition-colors" title="إضافة فئة">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="categoriesList" class="space-y-3"></div>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4 space-y-2">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">قوالب جاهزة</div>
                <button onclick="loadTemplate('manufacturing')" class="w-full px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded text-xs transition-colors text-right flex items-center gap-2">
                    <i data-lucide="factory" class="w-3 h-3"></i>
                    قالب التصنيع (6Ms)
                </button>
                <button onclick="loadTemplate('service')" class="w-full px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded text-xs transition-colors text-right flex items-center gap-2">
                    <i data-lucide="headphones" class="w-3 h-3"></i>
                    قالب الخدمات (4Ps)
                </button>
                <button onclick="loadTemplate('software')" class="w-full px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded text-xs transition-colors text-right flex items-center gap-2">
                    <i data-lucide="code" class="w-3 h-3"></i>
                    قالب البرمجيات
                </button>
            </div>
        </div>

        <div class="p-6 border-t border-slate-700 bg-slate-800/50 space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <button onclick="exportJSON()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded text-sm font-medium transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="file-json" class="w-4 h-4"></i>
                    حفظ JSON
                </button>
                <button onclick="importJSON()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded text-sm font-medium transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    فتح ملف
                </button>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="resetData()" class="px-4 py-2 bg-red-900/50 hover:bg-red-900/70 rounded text-sm font-medium transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    مسح
                </button>
                <button onclick="exportImage()" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 rounded text-sm font-medium transition-all shadow-lg shadow-cyan-900/20 flex justify-center items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    صورة PNG
                </button>
            </div>
            <div id="statusMsg" class="text-center text-xs text-emerald-400 h-4 opacity-0 transition-opacity flex items-center justify-center gap-1">
                <i data-lucide="check-circle" class="w-3 h-3"></i>
                تم الحفظ تلقائياً
            </div>
        </div>
    </aside>

    <main class="flex-1 relative bg-slate-900 overflow-hidden flex flex-col order-1 md:order-2">
        
        <div class="absolute inset-0 pointer-events-none flex items-center justify-center overflow-hidden z-0 opacity-10">
            <svg width="800" height="600" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg" class="watermark-anim">
                <defs>
                    <linearGradient id="fishGrad" x1="0" y1="150" x2="400" y2="150" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.1"/>
                        <stop offset="100%" stop-color="#0284c7" stop-opacity="0.3"/>
                    </linearGradient>
                </defs>
                
                <path d="M380 150 L40 150" stroke="#38bdf8" stroke-width="2" stroke-linecap="round"/>
                
                <path d="M380 150 L400 110 Q390 150 400 190 L380 150" fill="url(#fishGrad)" stroke="#38bdf8" stroke-width="2"/>
                
                <path d="M40 150 C40 100 90 80 140 80 C180 80 200 120 200 150 C200 180 180 220 140 220 C90 220 40 200 40 150 Z" fill="url(#fishGrad)" stroke="#38bdf8" stroke-width="2"/>
                <circle cx="80" cy="130" r="8" fill="rgba(255,255,255,0.2)" stroke="#38bdf8"/>
                
                <g stroke="#38bdf8" stroke-width="1" opacity="0.4">
                    <path d="M180 150 L220 90" />
                    <path d="M240 150 L280 90" />
                    <path d="M300 150 L340 90" />
                    
                    <path d="M180 150 L220 210" />
                    <path d="M240 150 L280 210" />
                    <path d="M300 150 L340 210" />
                </g>
            </svg>
        </div>

        <div class="absolute top-4 left-4 right-4 z-10 flex justify-between items-center" dir="ltr">
             <div class="bg-slate-800/80 backdrop-blur px-3 py-1.5 rounded border border-slate-700 text-xs text-slate-300 flex items-center gap-2">
                <i data-lucide="info" class="w-3 h-3"></i>
                <span id="categoryCount">0 categories</span>
            </div>
            <div class="bg-slate-800/80 backdrop-blur px-3 py-1.5 rounded border border-slate-700 text-xs text-slate-300 flex items-center gap-2">
                <span>اسحب للتحرك • استخدم العجلة للتكبير</span>
                <i data-lucide="mouse-pointer-2" class="w-3 h-3"></i>
            </div>
        </div>

        <div class="canvas-container w-full h-full overflow-x-auto overflow-y-hidden relative z-10" id="canvasWrapper">
            <canvas id="fishboneCanvas"></canvas>
        </div>

        <div id="actionDrawer" class="action-drawer shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
            <div onclick="toggleDrawer()" class="action-tab hover:bg-cyan-400 transition-colors">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                <span>خطة العمل والتنفيذ</span>
                <i id="drawerIcon" data-lucide="chevron-up" class="w-4 h-4 transition-transform"></i>
            </div>
            
            <div class="p-8 h-full overflow-y-auto">
                <div class="max-w-7xl mx-auto h-full">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-white">منهجية حل المشكلات (Action Plan)</h2>
                        <button onclick="toggleDrawer()" class="text-slate-400 hover:text-white">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-20">
                        <div class="step-card border-l-4 border-l-red-500">
                            <h3><i data-lucide="list-ordered" class="w-5 h-5"></i> 1. ترتيب الأسباب (Prioritize)</h3>
                            <p class="text-xs text-slate-400 mb-2">حدد الأسباب الأكثر احتمالاً بناءً على البيانات.</p>
                            <textarea id="act_prioritize" oninput="updateActionPlan('prioritize', this.value)" class="flex-1 w-full bg-slate-800/50 rounded p-2 text-sm resize-none" placeholder="اكتب الأسباب ذات الأولوية..."></textarea>
                        </div>
                        
                        <div class="step-card border-l-4 border-l-orange-500">
                            <h3><i data-lucide="search-check" class="w-5 h-5"></i> 2. التحقق (Validate)</h3>
                            <p class="text-xs text-slate-400 mb-2">اختبر الأسباب للتأكد من أنها الجذر الحقيقي.</p>
                            <textarea id="act_validate" oninput="updateActionPlan('validate', this.value)" class="flex-1 w-full bg-slate-800/50 rounded p-2 text-sm resize-none" placeholder="كيف ستتحقق من صحة هذه الأسباب؟"></textarea>
                        </div>

                        <div class="step-card border-l-4 border-l-yellow-500">
                            <h3><i data-lucide="lightbulb" class="w-5 h-5"></i> 3. تطوير الحلول (Develop)</h3>
                            <p class="text-xs text-slate-400 mb-2">اقترح حلولاً تصحيحية للأسباب الجذرية.</p>
                            <textarea id="act_develop" oninput="updateActionPlan('develop', this.value)" class="flex-1 w-full bg-slate-800/50 rounded p-2 text-sm resize-none" placeholder="قائمة الحلول المقترحة..."></textarea>
                        </div>

                        <div class="step-card border-l-4 border-l-green-500">
                            <h3><i data-lucide="play-circle" class="w-5 h-5"></i> 4. التنفيذ (Implement)</h3>
                            <p class="text-xs text-slate-400 mb-2">ضع خطة زمنية ومسؤوليات لتطبيق الحلول.</p>
                            <textarea id="act_implement" oninput="updateActionPlan('implement', this.value)" class="flex-1 w-full bg-slate-800/50 rounded p-2 text-sm resize-none" placeholder="خطة التنفيذ (من؟ متى؟)..."></textarea>
                        </div>

                        <div class="step-card border-l-4 border-l-blue-500">
                            <h3><i data-lucide="activity" class="w-5 h-5"></i> 5. المراقبة (Monitor)</h3>
                            <p class="text-xs text-slate-400 mb-2">كيف ستقيس نجاح الحل؟</p>
                            <textarea id="act_monitor" oninput="updateActionPlan('monitor', this.value)" class="flex-1 w-full bg-slate-800/50 rounded p-2 text-sm resize-none" placeholder="مؤشرات الأداء وآلية المتابعة..."></textarea>
                        </div>

                        <div class="step-card border-l-4 border-l-purple-500">
                            <h3><i data-lucide="file-check" class="w-5 h-5"></i> 6. التوثيق (Finalize)</h3>
                            <p class="text-xs text-slate-400 mb-2">توثيق الدروس المستفادة ومنع التكرار.</p>
                            <textarea id="act_finalize" oninput="updateActionPlan('finalize', this.value)" class="flex-1 w-full bg-slate-800/50 rounded p-2 text-sm resize-none" placeholder="الخلاصة والدروس المستفادة..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <input type="file" id="fileInput" accept=".json" style="display: none;" onchange="handleFileImport(event)">

<script>
    // --- State Management ---
    const STORAGE_KEY = 'ishikawa_ar_v1';
    
    let appData = {
        problem: "توقف الخادم عن العمل",
        categories: [
            { id: 'c1', name: "الأجهزة (Hardware)", top: true, color: '#ef4444', causes: ["عطل في القرص", "ارتفاع الحرارة", "مشاكل الطاقة"] },
            { id: 'c2', name: "البرمجيات (Software)", top: false, color: '#f59e0b', causes: ["تسرب الذاكرة", "خطأ في التحديث", "إعدادات خاطئة"] },
            { id: 'c3', name: "الشبكة (Network)", top: true, color: '#10b981', causes: ["بطء الاتصال", "فقدان الحزم", "الجدار الناري"] },
            { id: 'c4', name: "العنصر البشري", top: false, color: '#3b82f6', causes: ["خطأ في التشغيل", "حذف غير مقصود", "نقص التدريب"] }
        ],
        actionPlan: {
            prioritize: "",
            validate: "",
            develop: "",
            implement: "",
            monitor: "",
            finalize: ""
        }
    };

    // --- Templates (Translated) ---
    const templates = {
        manufacturing: {
            problem: "ارتفاع نسبة العيوب في المنتج",
            categories: [
                { id: 't1', name: "الإنسان (Man)", top: true, color: '#ef4444', causes: ["نقص التدريب", "الإرهاق", "نقص المهارات"] },
                { id: 't2', name: "الآلة (Machine)", top: false, color: '#f59e0b', causes: ["تآكل المعدات", "مشاكل المعايرة", "تقنية قديمة"] },
                { id: 't3', name: "المواد (Material)", top: true, color: '#10b981', causes: ["خام رديء", "مواصفات خاطئة", "مشاكل التخزين"] },
                { id: 't4', name: "الطريقة (Method)", top: false, color: '#3b82f6', causes: ["إجراءات قديمة", "عدم وجود معيار", "عملية معقدة"] },
                { id: 't5', name: "القياس (Measurement)", top: true, color: '#8b5cf6', causes: ["أدوات غير دقيقة", "خطأ بشري", "مقاييس خاطئة"] },
                { id: 't6', name: "البيئة (Environment)", top: false, color: '#ec4899', causes: ["تغير الحرارة", "الغبار", "الرطوبة"] }
            ]
        },
        service: {
            problem: "انخفاض رضا العملاء",
            categories: [
                { id: 's1', name: "الموظفين", top: true, color: '#ef4444', causes: ["غير مدربين", "سلوك سيء", "حاجز اللغة"] },
                { id: 's2', name: "العمليات", top: false, color: '#f59e0b', causes: ["وقت انتظار طويل", "إجراءات معقدة", "خدمة غير متسقة"] },
                { id: 's3', name: "التكنولوجيا", top: true, color: '#10b981', causes: ["توقف النظام", "استجابة بطيئة", "واجهة صعبة"] },
                { id: 's4', name: "السياسات", top: false, color: '#3b82f6', causes: ["قواعد صارمة", "شروط غير واضحة", "رسوم مخفية"] }
            ]
        },
        software: {
            problem: "مشاكل أداء التطبيق",
            categories: [
                { id: 'sw1', name: "الكود (Code)", top: true, color: '#ef4444', causes: ["خوارزميات بطيئة", "ديون تقنية", "معمارية سيئة"] },
                { id: 'sw2', name: "البنية التحتية", top: false, color: '#f59e0b', causes: ["ضغط السيرفر", "بطء الشبكة", "عنق زجاجة الداتا"] },
                { id: 'sw3', name: "البيانات", top: true, color: '#10b981', causes: ["بيانات ضخمة", "استعلام غير محسن", "نقص الفهارس"] },
                { id: 'sw4', name: "خارجي", top: false, color: '#3b82f6', causes: ["API طرف ثالث", "هجمات DDoS", "توافق المتصفح"] }
            ]
        }
    };

    // --- Initialization ---
    document.addEventListener('DOMContentLoaded', () => {
        loadData();
        lucide.createIcons();
        setupCanvas();
        renderUI();
        updateCategoryCount();
        fillActionPlanInputs();
        window.addEventListener('resize', resizeCanvas);
        document.getElementById('problemInput').addEventListener('input', (e) => {
            appData.problem = e.target.value;
            saveData();
            requestAnimationFrame(draw);
        });
    });

    function loadData() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const parsed = JSON.parse(saved);
                appData = { ...appData, ...parsed }; // Merge to ensure structure
                if(!appData.actionPlan) appData.actionPlan = { prioritize: "", validate: "", develop: "", implement: "", monitor: "", finalize: "" };
            }
        } catch (e) { console.error("Storage access denied"); }
        document.getElementById('problemInput').value = appData.problem;
    }

    function saveData() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(appData));
            showStatus();
        } catch (e) { console.error("Could not save"); }
    }

    function showStatus() {
        const el = document.getElementById('statusMsg');
        el.style.opacity = '1';
        setTimeout(() => el.style.opacity = '0', 2000);
    }

    function resetData() {
        if(confirm("⚠️ سيتم حذف جميع البيانات والعودة للوضع الافتراضي. هل أنت متأكد؟")) {
            localStorage.removeItem(STORAGE_KEY);
            location.reload();
        }
    }

    function loadTemplate(type) {
        if(confirm(`هل تريد تحميل قالب ${type}؟ سيتم استبدال البيانات الحالية.`)) {
            appData.problem = templates[type].problem;
            appData.categories = JSON.parse(JSON.stringify(templates[type].categories));
            document.getElementById('problemInput').value = appData.problem;
            saveData();
            renderUI();
            updateCategoryCount();
            draw();
        }
    }

    function updateCategoryCount() {
        const count = appData.categories.length;
        const totalCauses = appData.categories.reduce((sum, cat) => sum + cat.causes.length, 0);
        document.getElementById('categoryCount').textContent = `${count} فئات • ${totalCauses} أسباب`;
    }

    // --- Action Plan Logic ---
    function toggleDrawer() {
        const drawer = document.getElementById('actionDrawer');
        const icon = document.getElementById('drawerIcon');
        drawer.classList.toggle('open');
        if(drawer.classList.contains('open')) {
            icon.style.transform = 'rotate(180deg)';
        } else {
            icon.style.transform = 'rotate(0deg)';
        }
    }

    function updateActionPlan(field, value) {
        appData.actionPlan[field] = value;
        saveData();
    }

    function fillActionPlanInputs() {
        if(!appData.actionPlan) return;
        for (const [key, value] of Object.entries(appData.actionPlan)) {
            const el = document.getElementById(`act_${key}`);
            if(el) el.value = value || "";
        }
    }

    // --- UI Rendering ---
    function renderUI() {
        const list = document.getElementById('categoriesList');
        list.innerHTML = '';

        if (appData.categories.length === 0) {
            list.innerHTML = `
                <div class="text-center py-8 text-slate-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">لا توجد فئات</p>
                    <p class="text-xs mt-1">اضغط + لإضافة الفئة الأولى</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        appData.categories.forEach((cat, index) => {
            const div = document.createElement('div');
            div.className = 'bg-slate-800 p-3 rounded border border-slate-700 list-item-anim group hover:border-slate-600 transition-colors';
            
            let causesHtml = cat.causes.map((cause, cIndex) => `
                <div class="flex items-center gap-2 mt-2 animate-[slideIn_0.2s_ease-out]">
                    <div class="w-1.5 h-1.5 rounded-full" style="background-color: ${cat.color}"></div>
                    <input type="text" value="${escapeHtml(cause)}" 
                        onchange="updateCause('${cat.id}', ${cIndex}, this.value)"
                        class="flex-1 bg-slate-900/50 border-0 border-b border-slate-600 rounded px-2 py-1 text-xs focus:border-cyan-500 text-right">
                    <button onclick="deleteCause('${cat.id}', ${cIndex})" class="text-slate-500 hover:text-red-400" title="حذف">
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                </div>
            `).join('');

            div.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-2 flex-1">
                        <input type="color" value="${cat.color}" 
                            onchange="updateCategoryColor('${cat.id}', this.value)"
                            class="category-color-picker" title="تغيير اللون">
                        <span class="text-xs font-mono text-slate-500">#${index+1}</span>
                        <input type="text" value="${escapeHtml(cat.name)}" 
                            onchange="updateCategoryName('${cat.id}', this.value)"
                            class="bg-transparent border-b border-transparent hover:border-slate-600 focus:border-cyan-400 font-semibold text-sm w-full px-1 py-0.5 transition-colors text-right">
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="moveCategory('${cat.id}', -1)" class="p-1 hover:bg-slate-700 rounded text-slate-400" title="تحريك لأعلى">
                            <i data-lucide="arrow-up" class="w-3 h-3"></i>
                        </button>
                        <button onclick="moveCategory('${cat.id}', 1)" class="p-1 hover:bg-slate-700 rounded text-slate-400" title="تحريك لأسفل">
                            <i data-lucide="arrow-down" class="w-3 h-3"></i>
                        </button>
                        <button onclick="togglePosition('${cat.id}')" class="p-1 hover:bg-slate-700 rounded" style="color: ${cat.color}" title="تبديل أعلى/أسفل">
                            <i data-lucide="${cat.top ? 'arrow-up-circle' : 'arrow-down-circle'}" class="w-3 h-3"></i>
                        </button>
                        <button onclick="deleteCategory('${cat.id}')" class="p-1 hover:bg-slate-700 rounded text-red-400" title="حذف الفئة">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
                <div class="pr-4 space-y-1">
                    ${causesHtml}
                    <button onclick="addCause('${cat.id}')" class="mt-2 w-full py-1 border border-dashed border-slate-600 text-slate-400 hover:text-white hover:border-slate-400 rounded text-xs flex items-center justify-center gap-1 transition-colors">
                        <i data-lucide="plus" class="w-3 h-3"></i> إضافة سبب
                    </button>
                </div>
            `;
            list.appendChild(div);
        });
        lucide.createIcons();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // --- Data Logic ---
    function addCategory() {
        const colors = ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];
        const newCat = { 
            id: 'c' + Date.now(), 
            name: "فئة جديدة", 
            top: appData.categories.length % 2 === 0, 
            color: colors[appData.categories.length % colors.length],
            causes: [] 
        };
        appData.categories.push(newCat);
        saveData(); renderUI(); updateCategoryCount(); draw();
    }

    function updateCategoryName(id, newName) { 
        const cat = appData.categories.find(c => c.id === id);
        if (cat) { cat.name = newName; saveData(); draw(); }
    }

    function updateCategoryColor(id, newColor) { 
        const cat = appData.categories.find(c => c.id === id);
        if (cat) { cat.color = newColor; saveData(); renderUI(); draw(); }
    }

    function deleteCategory(id) {
        if(confirm("حذف هذه الفئة وجميع الأسباب المرتبطة بها؟")) {
            appData.categories = appData.categories.filter(c => c.id !== id);
            saveData(); renderUI(); updateCategoryCount(); draw();
        }
    }

    function moveCategory(id, direction) {
        const idx = appData.categories.findIndex(c => c.id === id);
        if (idx < 0) return;
        const newIdx = idx + direction;
        if (newIdx >= 0 && newIdx < appData.categories.length) {
            [appData.categories[idx], appData.categories[newIdx]] = [appData.categories[newIdx], appData.categories[idx]];
            saveData(); renderUI(); draw();
        }
    }

    function togglePosition(id) {
        const cat = appData.categories.find(c => c.id === id);
        if (cat) { cat.top = !cat.top; saveData(); renderUI(); draw(); }
    }

    function addCause(catId) {
        const cat = appData.categories.find(c => c.id === catId);
        if (cat) { 
            cat.causes.push("سبب جديد"); 
            saveData(); renderUI(); updateCategoryCount(); draw(); 
        }
    }

    function updateCause(catId, causeIdx, newVal) {
        const cat = appData.categories.find(c => c.id === catId);
        if (cat && cat.causes[causeIdx] !== undefined) {
            cat.causes[causeIdx] = newVal; saveData(); draw();
        }
    }

    function deleteCause(catId, causeIdx) {
        const cat = appData.categories.find(c => c.id === catId);
        if (cat) { 
            cat.causes.splice(causeIdx, 1); 
            saveData(); renderUI(); updateCategoryCount(); draw(); 
        }
    }

    // --- Import/Export ---
    function exportJSON() {
        const dataStr = JSON.stringify(appData, null, 2);
        const blob = new Blob([dataStr], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `ishikawa-ar-${Date.now()}.json`;
        link.click();
        URL.revokeObjectURL(url);
    }

    function importJSON() {
        document.getElementById('fileInput').click();
    }

    function handleFileImport(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const imported = JSON.parse(e.target.result);
                if (imported.problem !== undefined && Array.isArray(imported.categories)) {
                    appData = imported;
                    document.getElementById('problemInput').value = appData.problem;
                    fillActionPlanInputs();
                    saveData(); renderUI(); updateCategoryCount(); draw();
                    alert('✅ تم استيراد البيانات بنجاح!');
                } else { alert('❌ صيغة الملف غير صحيحة'); }
            } catch (err) { alert('❌ خطأ في القراءة: ' + err.message); }
        };
        reader.readAsText(file);
        event.target.value = '';
    }

    // --- Canvas Drawing Engine (RTL Adjusted) ---
    const canvas = document.getElementById('fishboneCanvas');
    const ctx = canvas.getContext('2d');

    function setupCanvas() { resizeCanvas(); }
    
    function resizeCanvas() {
        const wrapper = document.getElementById('canvasWrapper');
        const minWidth = wrapper.clientWidth;
        const calculatedWidth = 800 + (appData.categories.length * 280);
        canvas.width = Math.max(minWidth, calculatedWidth, 1600);
        canvas.height = wrapper.clientHeight || 800;
        draw();
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        const centerY = canvas.height / 2;
        
        // RTL Configurations
        // Head is on Left (Small X), Tail is on Right (Large X)
        const headX = 150; 
        const tailX = canvas.width - 150;
        const spineY = centerY;

        // 1. Main Spine (Horizontal arrow pointing Left)
        ctx.beginPath();
        ctx.moveTo(tailX, spineY); // Start at Tail
        ctx.lineTo(headX + 80, spineY); // End before Head
        ctx.lineWidth = 5;
        ctx.strokeStyle = '#94a3b8';
        ctx.lineCap = 'round';
        ctx.stroke();

        // Arrowhead for Spine (Pointing Left)
        ctx.beginPath();
        ctx.moveTo(headX + 90, spineY - 10);
        ctx.lineTo(headX + 70, spineY);
        ctx.lineTo(headX + 90, spineY + 10);
        ctx.fillStyle = '#94a3b8';
        ctx.fill();

        // 2. Fish Tail (On the Right)
        ctx.beginPath();
        ctx.moveTo(tailX, spineY);
        ctx.lineTo(tailX + 40, spineY - 40);
        ctx.lineTo(tailX + 40, spineY + 40);
        ctx.closePath();
        ctx.fillStyle = '#64748b';
        ctx.fill();
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 2;
        ctx.stroke();

        // 3. Head (Problem Box - Left Side)
        ctx.save();
        ctx.translate(headX, spineY);
        const headW = 280;
        const headH = 100;
        
        // Shadow
        ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
        ctx.shadowBlur = 15;
        ctx.shadowOffsetX = 5;
        ctx.shadowOffsetY = 5;
        
        // Draw centered on 0,0 relative to translation
        // Actually, let's draw it such that the right edge connects to spine
        // headX is the center of the box roughly
        roundRect(ctx, -headW/2, -headH/2, headW, headH, 12, true, '#1e293b', '#38bdf8');
        
        ctx.shadowColor = 'transparent';
        
        // Icon
        ctx.fillStyle = '#38bdf8';
        ctx.font = '24px Cairo';
        ctx.textAlign = 'center';
        // ctx.fillText('⚠', 0, -30);
        
        // Text
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 16px Cairo';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        wrapText(ctx, appData.problem, 0, 0, headW - 40, 24);
        ctx.restore();

        // 4. Categories
        if (appData.categories.length === 0) {
            ctx.fillStyle = '#475569';
            ctx.font = '18px Cairo';
            ctx.textAlign = 'center';
            ctx.fillText('أضف فئات من القائمة الجانبية للبدء', canvas.width / 2, centerY);
            return;
        }

        const availableSpineLength = tailX - (headX + 80);
        const stepX = availableSpineLength / (appData.categories.length + 1);

        appData.categories.forEach((cat, index) => {
            // Calculate X from Head moving Right towards Tail
            const spineX = (headX + 120) + (stepX * (index + 0.5));
            const isTop = cat.top;
            
            const vLineHeight = 220;
            const vLineStartY = isTop ? spineY - vLineHeight : spineY;
            const vLineEndY = isTop ? spineY : spineY + vLineHeight;
            
            // Draw Vertical Category Line
            ctx.beginPath();
            ctx.moveTo(spineX, vLineStartY);
            ctx.lineTo(spineX, vLineEndY);
            ctx.lineWidth = 4;
            ctx.strokeStyle = cat.color;
            ctx.lineCap = 'round';
            ctx.stroke();

            // Category Label Box
            const labelY = isTop ? vLineStartY - 30 : vLineEndY + 30;
            const labelBoxW = 160;
            const labelBoxH = 36;
            
            ctx.fillStyle = cat.color;
            roundRect(ctx, spineX - labelBoxW/2, labelY - labelBoxH/2, labelBoxW, labelBoxH, 6, true, cat.color);
            
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px Cairo';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(cat.name, spineX, labelY + 2); // +2 for visual centering with Arabic font

            // 5. Causes (diagonal branches)
            if (cat.causes.length > 0) {
                const segmentHeight = vLineHeight / (cat.causes.length + 1);
                
                cat.causes.forEach((cause, cIndex) => {
                    const causeY = isTop 
                        ? vLineEndY - (segmentHeight * (cIndex + 1)) 
                        : vLineStartY + (segmentHeight * (cIndex + 1));
                    
                    // In RTL, branches usually flow "with" the spine (Right to Left) 
                    // or "against" it to create the bone look.
                    // Visual standard: Ribs angle towards the tail (Right) usually? 
                    // Let's make them angle towards the Right (Tail) to look like standard fishbone ribs.
                    
                    const angleDeg = isTop ? -45 : 45; // Pointing Right
                    const angleRad = angleDeg * (Math.PI / 180);
                    const branchLen = 130;

                    const endX = spineX + Math.cos(angleRad) * branchLen;
                    const endY = causeY + Math.sin(angleRad) * branchLen;

                    // Branch line
                    ctx.beginPath();
                    ctx.moveTo(spineX, causeY);
                    ctx.lineTo(endX, endY);
                    ctx.lineWidth = 2;
                    ctx.strokeStyle = lightenColor(cat.color, 30);
                    ctx.stroke();

                    // Cause dot
                    ctx.beginPath();
                    ctx.arc(endX, endY, 4, 0, Math.PI * 2);
                    ctx.fillStyle = cat.color;
                    ctx.fill();

                    // Cause text box
                    ctx.font = '13px Cairo';
                    const textMetrics = ctx.measureText(cause);
                    const textW = textMetrics.width + 16;
                    const textH = 24;
                    
                    // Text placed at end of branch
                    const textX = endX + 10; // Slightly right of the dot
                    const textY = endY - textH/2;
                    
                    ctx.fillStyle = 'rgba(30, 41, 59, 0.9)';
                    roundRect(ctx, textX, textY, textW, textH, 4, true, 'rgba(30, 41, 59, 0.9)');
                    
                    ctx.fillStyle = '#e2e8f0';
                    ctx.textAlign = 'left'; // Text flows right in RTL, but alignment is LTR relative to canvas coord? No, standard canvas text.
                    ctx.textBaseline = 'middle';
                    ctx.fillText(cause, textX + 8, endY + 2);
                });
            }
        });
    }

    // --- Helper Functions ---
    function roundRect(ctx, x, y, width, height, radius, fill, fillColor, strokeColor) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
        if (fill && fillColor) { ctx.fillStyle = fillColor; ctx.fill(); }
        if (strokeColor) { ctx.strokeStyle = strokeColor; ctx.lineWidth = 3; ctx.stroke(); }
    }

    function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
        const words = text.split(' ');
        let line = '';
        let lineArray = [];
        for(let n = 0; n < words.length; n++) {
            let testLine = line + words[n] + ' ';
            if (ctx.measureText(testLine).width > maxWidth && n > 0) {
                lineArray.push(line);
                line = words[n] + ' ';
            } else { line = testLine; }
        }
        lineArray.push(line);
        const totalHeight = lineArray.length * lineHeight;
        let startY = y - (totalHeight / 2) + (lineHeight/2);
        for(let k = 0; k < lineArray.length; k++) {
            ctx.fillText(lineArray[k], x, startY + (k*lineHeight));
        }
    }

    function lightenColor(color, percent) {
        const num = parseInt(color.replace("#",""), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
    }

    function exportImage() {
        const link = document.createElement('a');
        link.download = `ishikawa-diagram-${Date.now()}.png`;
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        const tCtx = tempCanvas.getContext('2d');
        
        // Background
        tCtx.fillStyle = '#0f172a';
        tCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        
        // Grid pattern
        tCtx.fillStyle = '#1e293b';
        for(let x = 0; x < tempCanvas.width; x += 20) {
            for(let y = 0; y < tempCanvas.height; y += 20) {
                tCtx.fillRect(x, y, 1, 1);
            }
        }
        
        // Diagram
        tCtx.drawImage(canvas, 0, 0);
        
        // Watermark
        tCtx.font = '14px Cairo';
        tCtx.fillStyle = 'rgba(226, 232, 240, 0.3)';
        tCtx.textAlign = 'right';
        tCtx.fillText('تم الإنشاء بواسطة أداة إيشيكاوا', tempCanvas.width - 20, tempCanvas.height - 20);
        
        link.href = tempCanvas.toDataURL('image/png');
        link.click();
    }
</script>
</body>
</html>