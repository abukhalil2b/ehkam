<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الهيكل التنظيمي</title>
    
    <!-- خطوط جوجل (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- مكتبة أيقونات Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN للمحاكاة) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- إعدادات Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Styles for the Tree Structure */
        body {
            background-color: #f8fafc;
            overflow: hidden; /* لمنع سكرول الصفحة الافتراضي واستخدام السكرول الداخلي */
            user-select: none; /* لتحسين تجربة السحب */
        }

        /* حاوية اللوحة القابلة للتحريك */
        #chart-container {
            width: 100%;
            height: calc(100vh - 80px); /* ارتفاع الشاشة ناقص الهيدر */
            overflow: hidden;
            position: relative;
            cursor: grab;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }

        #chart-container:active {
            cursor: grabbing;
        }

        #tree-canvas {
            position: absolute;
            top: 50px;
            left: 50%; /* يبدأ المنتصف تقريباً */
            transform-origin: top center;
            transition: transform 0.1s ease-out; /* سلاسة الحركة */
            display: flex;
            justify-content: center;
        }

        /* بنية الشجرة CSS */
        .tree ul {
            padding-top: 20px;
            position: relative;
            transition: all 0.5s;
            display: flex;
            justify-content: center;
        }

        .tree li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 10px 0 10px;
            transition: all 0.5s;
        }

        /* خطوط التوصيل (Connectors) */
        .tree li::before, .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 2px solid #94a3b8;
            width: 50%;
            height: 20px;
        }

        .tree li::after {
            right: auto;
            left: 50%;
            border-left: 2px solid #94a3b8;
        }

        /* إزالة الخطوط من العقدة الوحيدة */
        .tree li:only-child::after, .tree li:only-child::before {
            display: none;
        }

        .tree li:only-child {
            padding-top: 0;
        }

        /* إزالة الخط من العقدة الأولى والأخيرة لربط الأب */
        .tree li:first-child::before, .tree li:last-child::after {
            border: 0 none;
        }

        /* إضافة الخط العمودي للأب */
        .tree li:last-child::before {
            border-right: 2px solid #94a3b8;
            border-radius: 0 5px 0 0;
        }

        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
        }

        .tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 2px solid #94a3b8;
            width: 0;
            height: 20px;
        }

        /* بطاقة الوحدة (Node Card) */
        .node-card {
            display: inline-block;
            background: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            min-width: 160px;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .node-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
            border-color: #059669;
        }

        /* ألوان حسب نوع الوحدة */
        .type-Minister { border-top: 4px solid #7c3aed; } /* بنفسجي */
        .type-Undersecretary { border-top: 4px solid #db2777; } /* وردي */
        .type-Directorate { border-top: 4px solid #2563eb; } /* أزرق */
        .type-Department { border-top: 4px solid #ea580c; } /* برتقالي */
        .type-Section { border-top: 4px solid #16a34a; } /* أخضر */
        .type-Expert { border-top: 4px solid #475569; } /* رمادي */

        /* أيقونة الطي/التوسيع */
        .toggle-btn {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: #fff;
            border: 2px solid #94a3b8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            z-index: 20;
            color: #64748b;
            transition: all 0.2s;
        }
        .toggle-btn:hover {
            background: #059669;
            border-color: #059669;
            color: white;
        }

        /* Modal Animation */
        .modal {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            transform: scale(0.95);
            transition: all 0.3s ease-in-out;
        }
        .modal.active .modal-content {
            transform: scale(1);
        }
    </style>
</head>
<body>

    <!-- الهيدر (من الكود الأصلي) -->
    <header class="bg-white border-b border-gray-200 h-20 shadow-sm z-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800 flex items-center space-x-3 space-x-reverse">
                <span class="material-icons text-3xl text-emerald-600">account_tree</span>
                <span class="border-r pr-3 mr-3 border-gray-300">إدارة الهيكل التنظيمي</span>
            </h1>
            <div class="flex gap-2">
                <a href="#" onclick="alert('سيتم نقلك لصفحة الإضافة')" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 font-bold transform hover:-translate-y-0.5">
                    <span class="material-icons text-base">add</span>
                    <span>وحدة جديدة</span>
                </a>
            </div>
        </div>
    </header>

    <!-- شريط أدوات التحكم في الشجرة -->
    <div class="absolute top-24 left-6 z-40 bg-white p-2 rounded-lg shadow-md border border-gray-200 flex flex-col gap-2">
        <button onclick="zoomIn()" class="p-2 hover:bg-gray-100 rounded text-gray-600" title="تكبير">
            <span class="material-icons">add</span>
        </button>
        <button onclick="zoomOut()" class="p-2 hover:bg-gray-100 rounded text-gray-600" title="تصغير">
            <span class="material-icons">remove</span>
        </button>
        <button onclick="resetView()" class="p-2 hover:bg-gray-100 rounded text-gray-600" title="إعادة ضبط">
            <span class="material-icons">center_focus_strong</span>
        </button>
    </div>

    <!-- منطقة العرض التفاعلية -->
    <div id="chart-container">
        <div id="tree-canvas" class="tree">
            <!-- سيتم رسم الشجرة هنا بواسطة JavaScript -->
            <div class="flex items-center justify-center h-screen text-gray-400">
                <span class="material-icons animate-spin text-4xl mb-2">refresh</span>
                <span>جاري تحميل الهيكل التنظيمي...</span>
            </div>
        </div>
    </div>

    <!-- Modal: تفاصيل الوحدة -->
    <div id="unitModal" class="modal fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden mx-4">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 id="modalTitle" class="text-xl font-bold text-gray-800">اسم الوحدة</h2>
                    <span id="modalType" class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-gray-200 text-gray-700">نوع الوحدة</span>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <span class="material-icons">close</span>
                </button>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <div class="text-sm text-blue-600 mb-1 font-semibold">رمز الوحدة</div>
                        <div id="modalCode" class="text-lg font-bold text-gray-800">--</div>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                        <div class="text-sm text-emerald-600 mb-1 font-semibold">الترتيب الهيكلي</div>
                        <div id="modalOrder" class="text-lg font-bold text-gray-800">--</div>
                    </div>
                </div>

                <h3 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="material-icons text-lg text-emerald-600">badge</span>
                    المسميات الوظيفية والموظفين
                </h3>
                <div id="modalPositions" class="space-y-3 max-h-60 overflow-y-auto pr-2">
                    <!-- سيتم ملء القائمة هنا -->
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-200 transition">إغلاق</button>
                <a href="#" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-md flex items-center gap-1">
                    <span class="material-icons text-sm">edit</span> تعديل الوحدة
                </a>
            </div>
        </div>
    </div>

    <script>
        // --- 1. محاكاة البيانات (Mock Data) ---
        // هذه البيانات تحاكي ما يرسله الـ Controller في المتغير $rootUnit
        const rootUnitData = {
            id: 1,
            name: "وزارة الصحة",
            unit_code: "MOH-001",
            type: "Minister",
            hierarchy_order: 1,
            positions: [
                {
                    id: 101,
                    title: "وزير الصحة",
                    employees: [{
                        name: "د. أحمد العلي",
                        photo: "https://picsum.photos/seed/minister/50/50"
                    }]
                }
            ],
            children: [
                {
                    id: 2,
                    name: "مكتب الوزير",
                    unit_code: "MOH-002",
                    type: "Section",
                    hierarchy_order: 1,
                    positions: [],
                    children: []
                },
                {
                    id: 3,
                    name: "وكالة الوزارة",
                    unit_code: "MOH-003",
                    type: "Undersecretary",
                    hierarchy_order: 2,
                    positions: [
                        {
                            title: "وكيل الوزارة",
                            employees: [{
                                name: "م. سالم المحمد",
                                photo: "https://picsum.photos/seed/under/50/50"
                            }]
                        }
                    ],
                    children: [
                        {
                            id: 4,
                            name: "الشؤون الإدارية والمالية",
                            unit_code: "DIR-001",
                            type: "Directorate",
                            hierarchy_order: 1,
                            positions: [
                                {
                                    title: "مدير إدارة الشؤون",
                                    employees: [{
                                        name: "م. خالد عمر",
                                        photo: "https://picsum.photos/seed/dir1/50/50"
                                    }]
                                }
                            ],
                            children: [
                                {
                                    id: 5,
                                    name: "دائرة الموارد البشرية",
                                    unit_code: "DEP-001",
                                    type: "Department",
                                    hierarchy_order: 1,
                                    positions: [], children: []
                                },
                                {
                                    id: 6,
                                    name: "دائرة الميزانية والحسابات",
                                    unit_code: "DEP-002",
                                    type: "Department",
                                    hierarchy_order: 2,
                                    positions: [], children: []
                                }
                            ]
                        },
                        {
                            id: 7,
                            name: "الشؤون الفنية",
                            unit_code: "DIR-002",
                            type: "Directorate",
                            hierarchy_order: 2,
                            positions: [
                                {
                                    title: "مدير الشؤون الفنية",
                                    employees: [{
                                        name: "د. منى يوسف",
                                        photo: "https://picsum.photos/seed/dir2/50/50"
                                    }]
                                }
                            ],
                            children: []
                        }
                    ]
                }
            ]
        };

        // --- 2. منطق رسم الشجرة (Tree Rendering) ---
        
        // دالة مساعدة للحصول على اسم النوع بالعربي
        function getTypeLabel(type) {
            const labels = {
                'Minister': 'وزارة',
                'Undersecretary': 'وكالة',
                'Directorate': 'مديرية عامة',
                'Department': 'دائرة',
                'Section': 'قسم',
                'Expert': 'خبير'
            };
            return labels[type] || type;
        }

        // الدالة الرئيسية لبناء HTML العقدة
        function buildTreeHTML(node) {
            const hasChildren = node.children && node.children.length > 0;
            const typeClass = `type-${node.type}`;
            
            // تحديد الموظف الرئيسي (المدير) للوحدة
            let headOfUnitHtml = '';
            const mainPosition = node.positions && node.positions.length > 0 ? node.positions[0] : null;
            const employee = mainPosition && mainPosition.employees && mainPosition.employees.length > 0 ? mainPosition.employees[0] : null;

            if (employee) {
                headOfUnitHtml = `
                    <div class="mt-2 flex items-center justify-center gap-2 bg-gray-50 rounded p-1 border border-gray-100">
                        <img src="${employee.photo}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-bold text-gray-600 truncate max-w-[100px]">${employee.name}</span>
                    </div>
                `;
            } else {
                headOfUnitHtml = `<div class="h-6 mt-2 flex items-center justify-center"><span class="text-[10px] text-red-400 font-bold">شاغر</span></div>`;
            }

            // زر الطي/التوسيع
            const toggleBtn = hasChildren ? `<div class="toggle-btn" onclick="toggleChildren(this, event)">-</div>` : '';

            // بناء الأبناء العودية
            let childrenHtml = '';
            if (hasChildren) {
                // نقوم بترتيب الأبناء حسب الـ hierarchy_order ثم الاسم
                const sortedChildren = [...node.children].sort((a, b) => {
                    if (a.hierarchy_order !== b.hierarchy_order) return a.hierarchy_order - b.hierarchy_order;
                    return a.name.localeCompare(b.name);
                });

                childrenHtml = '<ul>';
                sortedChildren.forEach(child => {
                    childrenHtml += `<li>${buildTreeHTML(child)}</li>`;
                });
                childrenHtml += '</ul>';
            }

            return `
                <div class="relative">
                    <div class="node-card cursor-pointer ${typeClass}" onclick='openModal(${JSON.stringify(node).replace(/'/g, "&#39;")})'>
                        <div class="text-xs font-bold text-emerald-600 mb-1 tracking-wide">${getTypeLabel(node.type)}</div>
                        <div class="text-sm font-bold text-gray-800 leading-tight">${node.name}</div>
                        ${headOfUnitHtml}
                    </div>
                    ${toggleBtn}
                    ${childrenHtml}
                </div>
            `;
        }

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('tree-canvas');
            canvas.innerHTML = `<ul><li>${buildTreeHTML(rootUnitData)}</li></ul>`;
            
            // توسيط الشجرة مبدئياً
            centerTree();
        });

        // --- 3. التفاعلات (Pan & Zoom) ---
        
        let scale = 1;
        let pannedX = 0;
        let pannedY = 0;
        let isDragging = false;
        let startX, startY;

        const container = document.getElementById('chart-container');
        const canvas = document.getElementById('tree-canvas');

        function updateTransform() {
            // نستخدم left لتعويض الموضع لأننا استخدمنا absolute positioning
            canvas.style.transform = `translate(${pannedX}px, ${pannedY}px) scale(${scale})`;
            canvas.style.left = `calc(50% + ${pannedX}px)`; 
            // ملاحظة: للتبسيط، سنستخدم translate فقط ونلغي الـ left ونضبط الموضع المبدئي بـ CSS
            canvas.style.left = '50%'; 
            // للتحكم الصحيح في التكبير حول الماوس، الأمور معقدة، سنكتفي بالتكبير في المنتصف
            // سنستخدم translateX/Y في الـ transform فقط، وسنضبط top/left ثابتة
            canvas.style.top = '50px';
            canvas.style.left = '50%';
            canvas.style.marginLeft = '-50%'; // لجعل النقطة 50% هي الوسط
            
            // التصحيح للـ transform ليعمل بشكل صحيح مع margin-left
            canvas.style.transform = `translate(${pannedX}px, ${pannedY}px) scale(${scale})`;
        }

        function zoomIn() {
            scale += 0.1;
            updateTransform();
        }

        function zoomOut() {
            if (scale > 0.2) scale -= 0.1;
            updateTransform();
        }

        function resetView() {
            scale = 1;
            pannedX = 0;
            pannedY = 0;
            updateTransform();
        }

        function centerTree() {
            resetView();
        }

        // أحداث الماوس للسحب (Pan)
        container.addEventListener('mousedown', (e) => {
            if(e.target.closest('.node-card') || e.target.closest('.toggle-btn')) return; // لا تسحب عند النقر على الكروت
            isDragging = true;
            startX = e.clientX - pannedX;
            startY = e.clientY - pannedY;
            container.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            pannedX = e.clientX - startX;
            pannedY = e.clientY - startY;
            updateTransform();
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
            container.style.cursor = 'grab';
        });

        // دعم عجلة الماوس للتكبير
        container.addEventListener('wheel', (e) => {
            e.preventDefault();
            const scaleAmount = -e.deltaY * 0.001;
            scale += scaleAmount;
            scale = Math.min(Math.max(0.2, scale), 3); // حدود التكبير
            updateTransform();
        });

        // --- 4. المنطق التفاعلي للشجرة ---

        // طي/توسيع الفروع
        window.toggleChildren = function(btn, event) {
            event.stopPropagation(); // منع فتح المودال عند النقر على زر الطي
            const parentDiv = btn.closest('div').parentElement; // الـ li المحتوي
            const ul = parentDiv.querySelector('ul');
            
            if (ul) {
                if (ul.style.display === 'none') {
                    ul.style.display = 'flex';
                    btn.textContent = '-';
                    btn.style.transform = 'translateX(-50%) rotate(0deg)';
                } else {
                    ul.style.display = 'none';
                    btn.textContent = '+';
                    btn.style.transform = 'translateX(-50%) rotate(0deg)';
                }
            }
        };

        // --- 5. المودال (Modal) ---
        
        const modal = document.getElementById('unitModal');
        
        window.openModal = function(node) {
            document.getElementById('modalTitle').textContent = node.name;
            document.getElementById('modalCode').textContent = node.unit_code;
            document.getElementById('modalType').textContent = getTypeLabel(node.type);
            document.getElementById('modalOrder').textContent = node.hierarchy_order;
            
            const positionsContainer = document.getElementById('modalPositions');
            positionsContainer.innerHTML = '';

            if (!node.positions || node.positions.length === 0) {
                positionsContainer.innerHTML = `
                    <div class="text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <span class="text-gray-500 text-sm">لا توجد مسميات وظيفية مرتبطة</span>
                    </div>
                `;
            } else {
                node.positions.forEach(pos => {
                    const emp = pos.employees && pos.employees.length > 0 ? pos.employees[0] : null;
                    
                    let empHtml = '';
                    if (emp) {
                        empHtml = `
                            <div class="flex items-center gap-2 mr-2">
                                <img src="${emp.photo}" class="w-8 h-8 rounded-full border border-gray-200">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800">${emp.name}</span>
                                    <span class="text-[10px] text-emerald-600">شاغل حالياً</span>
                                </div>
                            </div>
                        `;
                    } else {
                        empHtml = `
                            <div class="mr-2 flex items-center gap-1 text-red-500 text-xs font-bold">
                                <span class="material-icons text-sm">person_outline</span> شاغر
                            </div>
                        `;
                    }

                    positionsContainer.innerHTML += `
                        <div class="flex justify-between items-center bg-white border border-gray-200 p-3 rounded-lg shadow-sm hover:border-emerald-300 transition">
                            <div class="font-semibold text-gray-700">${pos.title}</div>
                            ${empHtml}
                        </div>
                    `;
                });
            }

            modal.classList.add('active');
        };

        window.closeModal = function() {
            modal.classList.remove('active');
        };

        // إغلاق المودال عند النقر في الخارج
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

    </script>
</body>
</html>