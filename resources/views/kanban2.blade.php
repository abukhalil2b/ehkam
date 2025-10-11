<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <title>لوحة كانبان - توزيع الأعمال</title>
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .kanban-board {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 10px 0;
        }
        
        .employee-column {
            background-color: white;
            border-radius: 10px;
            min-width: 300px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .column-header {
            padding: 15px;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .ahmed .column-header { background-color: #3498db; }
        .hamdan .column-header { background-color: #2ecc71; }
        .hameed .column-header { background-color: #f39c12; }
        .masood .column-header { background-color: #9b59b6; }
        
        .tasks-container {
            padding: 15px;
            flex-grow: 1;
            min-height: 500px;
        }
        
        .task {
            background-color: var(--light);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            cursor: move;
            transition: transform 0.2s, box-shadow 0.2s;
            border-right: 4px solid;
        }
        
        .task:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .task h3 {
            font-size: 1rem;
            margin-bottom: 8px;
        }
        
        .task p {
            font-size: 0.9rem;
            color: #666;
        }
        
        .task-1 { border-color: #3498db; }
        .task-2 { border-color: #2ecc71; }
        .task-3 { border-color: #f39c12; }
        .task-4 { border-color: #e74c3c; }
        .task-5 { border-color: #9b59b6; }
        .task-6 { border-color: #1abc9c; }
        .task-7 { border-color: #34495e; }
        .task-8 { border-color: #d35400; }
        .task-9 { border-color: #7f8c8d; }
        .task-10 { border-color: #27ae60; }
        .task-11 { border-color: #8e44ad; }
        
        .task-priority {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            margin-top: 8px;
        }
        
        .priority-high { background-color: #ffebee; color: #e53935; }
        .priority-medium { background-color: #fff8e1; color: #ff8f00; }
        .priority-low { background-color: #e8f5e9; color: #43a047; }
        
        .drag-over {
            background-color: #f0f8ff;
            border: 2px dashed #3498db;
        }
        
        @media (max-width: 768px) {
            .kanban-board {
                flex-direction: column;
            }
            
            .employee-column {
                min-width: 100%;
            }
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <p class="subtitle">إدارة وتوزيع المهام بين الموظفين بطريقة مرئية وفعالة</p>
        </header>
        
        <div class="kanban-board" id="kanbanBoard">
            <!-- أحمد -->
            <div class="employee-column ahmed" data-employee="ahmed">
                <div class="column-header">أحمد</div>
                <div class="tasks-container" id="ahmed-tasks">
                    <div class="task task-1" draggable="true" data-task="1">
                        <h3>متابعة بريد الوزارة</h3>
                        <p>المتابعة اليومية لبريد الوزارة والرد على الاستفسارات</p>
                        <span class="task-priority priority-high">عالي</span>
                    </div>
                    <div class="task task-5" draggable="true" data-task="5">
                        <h3>توزيع الأدوار على الخطة</h3>
                        <p>توزيع المهام والأدوار على الموظفين حسب الخطة</p>
                        <span class="task-priority priority-medium">متوسط</span>
                    </div>
                    <div class="task task-11" draggable="true" data-task="11">
                        <h3>تصميم استمارة الحضور</h3>
                        <p>تصميم نموذج حضور الاجتماعات والفعاليات</p>
                        <span class="task-priority priority-low">منخفض</span>
                    </div>
                </div>
            </div>
            
            <!-- حمدان -->
            <div class="employee-column hamdan" data-employee="hamdan">
                <div class="column-header">حمدان</div>
                <div class="tasks-container" id="hamdan-tasks">
                    <div class="task task-2" draggable="true" data-task="2">
                        <h3>مراجعة مسودة خطة الوزارة 2026</h3>
                        <p>مراجعة شاملة لمسودة الخطة وتقديم الملاحظات</p>
                        <span class="task-priority priority-high">عالي</span>
                    </div>
                    <div class="task task-4" draggable="true" data-task="4">
                        <h3>التنسيق مع الإدارات للاجتماع القادم</h3>
                        <p>التواصل مع الإدارات المختلفة لتحديد موعد الاجتماع</p>
                        <span class="task-priority priority-medium">متوسط</span>
                    </div>
                    <div class="task task-10" draggable="true" data-task="10">
                        <h3>زيارة القطاعات والتوعية</h3>
                        <p>زيارة القطاعات المختلفة وتوعية الموظفين بالخطط الجديدة</p>
                        <span class="task-priority priority-medium">متوسط</span>
                    </div>
                </div>
            </div>
            
            <!-- حميد -->
            <div class="employee-column hameed" data-employee="hameed">
                <div class="column-header">حميد</div>
                <div class="tasks-container" id="hameed-tasks">
                    <div class="task task-3" draggable="true" data-task="3">
                        <h3>متابعة إنجاز المشاريع والأدلة الداعمة</h3>
                        <p>متابعة تقدم المشاريع وجمع الأدلة الداعمة للإنجاز</p>
                        <span class="task-priority priority-high">عالي</span>
                    </div>
                    <div class="task task-7" draggable="true" data-task="7">
                        <h3>كتابة محضر الاجتماع</h3>
                        <p>تدوين محضر الاجتماع وتوثيق القرارات والتوصيات</p>
                        <span class="task-priority priority-medium">متوسط</span>
                    </div>
                    <div class="task task-9" draggable="true" data-task="9">
                        <h3>تحليل البيانات الإحصائية</h3>
                        <p>تحليل البيانات المجمعة واستخلاص النتائج</p>
                        <span class="task-priority priority-low">منخفض</span>
                    </div>
                </div>
            </div>
            
            <!-- مسعود -->
            <div class="employee-column masood" data-employee="masood">
                <div class="column-header">مسعود</div>
                <div class="tasks-container" id="masood-tasks">
                    <div class="task task-6" draggable="true" data-task="6">
                        <h3>كتابة التقرير النهائي</h3>
                        <p>إعداد التقرير النهائي عن سير العمل والإنجازات</p>
                        <span class="task-priority priority-high">عالي</span>
                    </div>
                    <div class="task task-8" draggable="true" data-task="8">
                        <h3>تصميم الاستبانة لقياس رضى المستفيدين</h3>
                        <p>تصميم استبيان لقياس مستوى رضا المستفيدين عن الخدمات</p>
                        <span class="task-priority priority-medium">متوسط</span>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <p>لوحة كانبان لتوزيع الأعمال - تم التحديث بتاريخ <span id="currentDate"></span></p>
        </footer>
    </div>

    <script>
        // عرض التاريخ الحالي
        const currentDateElement = document.getElementById('currentDate');
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        currentDateElement.textContent = today.toLocaleDateString('ar-SA', options);
        
        // وظائف السحب والإفلات
        document.addEventListener('DOMContentLoaded', function() {
            const tasks = document.querySelectorAll('.task');
            const columns = document.querySelectorAll('.tasks-container');
            
            let draggedTask = null;
            
            // إضافة مستمعات الأحداث للمهام
            tasks.forEach(task => {
                task.addEventListener('dragstart', function() {
                    draggedTask = this;
                    setTimeout(() => this.style.opacity = '0.5', 0);
                });
                
                task.addEventListener('dragend', function() {
                    setTimeout(() => this.style.opacity = '1', 0);
                    draggedTask = null;
                });
            });
            
            // إضافة مستمعات الأحداث للأعمدة
            columns.forEach(column => {
                column.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('drag-over');
                });
                
                column.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });
                
                column.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');
                    
                    if (draggedTask && this !== draggedTask.parentNode) {
                        this.appendChild(draggedTask);
                        
                        // هنا يمكن إضافة كود لحفظ التغييرات في قاعدة البيانات
                        console.log(`تم نقل المهمة ${draggedTask.dataset.task} إلى ${this.parentNode.dataset.employee}`);
                    }
                });
            });
        });
    </script>
</body>
</html>