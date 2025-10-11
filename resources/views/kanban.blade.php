<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة كانبان - توزيع الأعمال</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .kanban-board {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .employee-column {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .employee-column::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--employee-color);
            border-radius: 20px 20px 0 0;
        }

        .employee-column:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .employee-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f2f6;
        }

        .employee-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--employee-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
        }

        .employee-info h3 {
            color: #2c3e50;
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .task-count {
            background: var(--employee-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .tasks-container {
            max-height: 600px;
            overflow-y: auto;
            padding-left: 5px;
        }

        .tasks-container::-webkit-scrollbar {
            width: 6px;
        }

        .tasks-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .tasks-container::-webkit-scrollbar-thumb {
            background: var(--employee-color);
            border-radius: 10px;
        }

        .task-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border-right: 4px solid var(--employee-color);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1));
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .task-card:hover::before {
            transform: translateX(100%);
        }

        .task-card:hover {
            transform: translateX(5px);
            background: #ffffff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .task-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .task-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .task-priority {
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .priority-high {
            background: #fee2e2;
            color: #dc2626;
        }

        .priority-medium {
            background: #fef3c7;
            color: #d97706;
        }

        .priority-low {
            background: #dcfce7;
            color: #16a34a;
        }

        .task-icon {
            margin-left: 8px;
            opacity: 0.7;
        }

        /* Employee color themes */
        .ahmed {
            --employee-color: #3498db;
        }

        .hamdan {
            --employee-color: #e74c3c;
        }

        .hameed {
            --employee-color: #2ecc71;
        }

        .masoud {
            --employee-color: #f39c12;
        }

        .stats-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .add-task-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .add-task-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            .kanban-board {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .stats-bar {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="header fade-in">
        <p>إدارة وتنظيم المهام بين أعضاء الفريق</p>
    </div>

    <div class="stats-bar fade-in">
        <div class="stat-item">
            <div class="stat-number">11</div>
            <div class="stat-label">إجمالي المهام</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">4</div>
            <div class="stat-label">أعضاء الفريق</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">2.75</div>
            <div class="stat-label">متوسط المهام</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">100%</div>
            <div class="stat-label">التوزيع المكتمل</div>
        </div>
    </div>

    <div class="kanban-board">
        <!-- عمود أحمد -->
        <div class="employee-column ahmed fade-in">
            <div class="employee-header">
                <div class="employee-avatar">أ</div>
                <div class="employee-info">
                    <h3>أحمد</h3>
                    <span class="task-count">3 مهام</span>
                </div>
            </div>
            <div class="tasks-container">
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-envelope task-icon"></i>
                        متابعة بريد الوزارة
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-high">عالية</span>
                        <span><i class="fas fa-clock"></i> يومي</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-users task-icon"></i>
                        التنسيق مع الإدارات للاجتماع القادم
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-medium">متوسطة</span>
                        <span><i class="fas fa-calendar"></i> أسبوعي</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-file-alt task-icon"></i>
                        كتابة محضر الاجتماع
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-medium">متوسطة</span>
                        <span><i class="fas fa-edit"></i> بعد كل اجتماع</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- عمود حمدان -->
        <div class="employee-column hamdan fade-in">
            <div class="employee-header">
                <div class="employee-avatar">ح</div>
                <div class="employee-info">
                    <h3>حمدان</h3>
                    <span class="task-count">3 مهام</span>
                </div>
            </div>
            <div class="tasks-container">
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-file-contract task-icon"></i>
                        مراجعة مسودة خطة الوزارة 2026
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-high">عالية</span>
                        <span><i class="fas fa-calendar-check"></i> شهري</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-tasks task-icon"></i>
                        توزيع الأدوار على الخطة
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-high">عالية</span>
                        <span><i class="fas fa-sitemap"></i> استراتيجي</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-file-signature task-icon"></i>
                        كتابة التقرير النهائي
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-medium">متوسطة</span>
                        <span><i class="fas fa-clock"></i> شهري</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- عمود حميد -->
        <div class="employee-column hameed fade-in">
            <div class="employee-header">
                <div class="employee-avatar">ح</div>
                <div class="employee-info">
                    <h3>حميد</h3>
                    <span class="task-count">3 مهام</span>
                </div>
            </div>
            <div class="tasks-container">
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-project-diagram task-icon"></i>
                        متابعة إنجاز المشاريع والأدلة الداعمة
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-high">عالية</span>
                        <span><i class="fas fa-chart-line"></i> أسبوعي</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-poll task-icon"></i>
                        تصميم الاستبانة لقياس رضى المستفيدين
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-medium">متوسطة</span>
                        <span><i class="fas fa-users"></i> فصلي</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-chart-bar task-icon"></i>
                        تحليل البيانات الإحصائية
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-medium">متوسطة</span>
                        <span><i class="fas fa-calculator"></i> شهري</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- عمود مسعود -->
        <div class="employee-column masoud fade-in">
            <div class="employee-header">
                <div class="employee-avatar">م</div>
                <div class="employee-info">
                    <h3>مسعود</h3>
                    <span class="task-count">2 مهام</span>
                </div>
            </div>
            <div class="tasks-container">
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-map-marked-alt task-icon"></i>
                        زيارة القطاعات والتوعية
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-medium">متوسطة</span>
                        <span><i class="fas fa-car"></i> ميداني</span>
                    </div>
                </div>
                <div class="task-card">
                    <div class="task-title">
                        <i class="fas fa-clipboard-list task-icon"></i>
                        تصميم استمارة الحضور
                    </div>
                    <div class="task-meta">
                        <span class="task-priority priority-low">منخفضة</span>
                        <span><i class="fas fa-check-circle"></i> حسب الحاجة</span>
                    </div>
                </div>
                <div class="empty-state">
                    <i class="fas fa-plus-circle"></i>
                    <p>يمكن إضافة مهام جديدة</p>
                </div>
            </div>
        </div>
    </div>

    <button class="add-task-btn pulse" onclick="showAddTaskModal()">
        <i class="fas fa-plus"></i>
    </button>

    <script>
        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all task cards
        document.querySelectorAll('.task-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Task card click handler
        document.querySelectorAll('.task-card').forEach(card => {
            card.addEventListener('click', function() {
                // Add ripple effect
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255,255,255,0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = '50%';
                ripple.style.top = '50%';
                ripple.style.width = ripple.style.height = '100px';
                ripple.style.marginLeft = ripple.style.marginTop = '-50px';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add task modal function (placeholder)
        function showAddTaskModal() {
            alert('سيتم فتح نافذة إضافة مهمة جديدة');
        }

        // Smooth scrolling for task containers
        document.querySelectorAll('.tasks-container').forEach(container => {
            let isDown = false;
            let startY;
            let scrollTop;

            container.addEventListener('mousedown', (e) => {
                isDown = true;
                startY = e.pageY - container.offsetTop;
                scrollTop = container.scrollTop;
            });

            container.addEventListener('mouseleave', () => {
                isDown = false;
            });

            container.addEventListener('mouseup', () => {
                isDown = false;
            });

            container.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const y = e.pageY - container.offsetTop;
                const walk = (y - startY) * 2;
                container.scrollTop = scrollTop - walk;
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Update stats dynamically
        function updateStats() {
            const totalTasks = document.querySelectorAll('.task-card').length - 1; // -1 for empty state
            const employees = document.querySelectorAll('.employee-column').length;
            const avgTasks = (totalTasks / employees).toFixed(2);
            
            document.querySelector('.stat-number').textContent = totalTasks;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add stagger animation to columns
            document.querySelectorAll('.employee-column').forEach((column, index) => {
                column.style.animationDelay = `${index * 0.2}s`;
            });
        });
    </script>
</body>
</html>