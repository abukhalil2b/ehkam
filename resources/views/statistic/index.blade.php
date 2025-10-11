<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة إحصائيات وزارة الأوقاف والشؤون الدينية - 2024</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header .subtitle {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 15px;
            padding: 20px;
            max-width: 100vw;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f2f6;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 12px;
            font-size: 1.2rem;
            color: white;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            flex: 1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .stat-item:hover {
            background: #e9ecef;
            border-right-color: #3498db;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 5px;
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
            line-height: 1.2;
        }

        .stat-detail {
            font-size: 0.7rem;
            color: #95a5a6;
            margin-top: 3px;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #ecf0f1;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            border-radius: 3px;
            transition: width 1.5s ease;
        }

        /* Color themes for different sections */
        .quran .section-icon { background: linear-gradient(45deg, #27ae60, #2ecc71); }
        .waqf .section-icon { background: linear-gradient(45deg, #8e44ad, #9b59b6); }
        .zakat .section-icon { background: linear-gradient(45deg, #f39c12, #e67e22); }
        .mosque .section-icon { background: linear-gradient(45deg, #3498db, #2980b9); }
        .fatwa .section-icon { background: linear-gradient(45deg, #e74c3c, #c0392b); }
        .guidance .section-icon { background: linear-gradient(45deg, #16a085, #1abc9c); }
        .digital .section-icon { background: linear-gradient(45deg, #34495e, #2c3e50); }
        .astronomy .section-icon { background: linear-gradient(45deg, #f1c40f, #f39c12); }
        .hajj .section-icon { background: linear-gradient(45deg, #9b59b6, #8e44ad); }
        .orphan .section-icon { background: linear-gradient(45deg, #e67e22, #d35400); }
        .hr .section-icon { background: linear-gradient(45deg, #2ecc71, #27ae60); }

        .full-width {
            grid-column: 1 / -1;
        }

        .two-col {
            grid-column: span 2;
        }

        @media (max-width: 1400px) {
            .dashboard-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
                padding: 15px;
            }
            .header h1 {
                font-size: 1.4rem;
            }
        }

        .floating-stats {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            z-index: 50;
            display: none;
        }

        @media (min-width: 1600px) {
            .floating-stats {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>لوحة إحصائيات وزارة الأوقاف والشؤون الدينية</h1>
        <div class="subtitle">البيانات الشاملة لعام 2024</div>
    </div>

    <div class="dashboard-container">
        <!-- القرآن الكريم -->
        <div class="section-card quran">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-quran-alt"></i>
                </div>
                <div class="section-title">قطاع القرآن الكريم</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">1,645</span>
                    <div class="stat-label">مدارس تعليم القرآن</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">100K</span>
                    <div class="stat-label">الدارسين</div>
                    <div class="stat-detail">84% إناث</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 84%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1,360</span>
                    <div class="stat-label">المعلمين</div>
                    <div class="stat-detail">97% نساء</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1,600</span>
                    <div class="stat-label">البرنامج الإلكتروني</div>
                </div>
            </div>
        </div>

        <!-- الأوقاف -->
        <div class="section-card waqf">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="section-title">قطاع الأوقاف</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">39K+</span>
                    <div class="stat-label">الأصول الوقفية</div>
                    <div class="stat-detail">49% مبانٍ</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">82%</span>
                    <div class="stat-label">للجوامع والمساجد</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 82%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">35</span>
                    <div class="stat-label">المؤسسات الوقفية</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">2.5M</span>
                    <div class="stat-label">ريال عوائد متوقعة</div>
                </div>
            </div>
        </div>

        <!-- الزكاة -->
        <div class="section-card zakat">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="section-title">قطاع الزكاة</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">13.84M</span>
                    <div class="stat-label">ريال محصلة وموزعة</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">50K+</span>
                    <div class="stat-label">أسر مستفيدة</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">67</span>
                    <div class="stat-label">لجان محلية</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">7.17M</span>
                    <div class="stat-label">تحويلات إلكترونية</div>
                </div>
            </div>
        </div>

        <!-- المساجد والجوامع -->
        <div class="section-card mosque">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-mosque"></i>
                </div>
                <div class="section-title">المساجد والجوامع</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">17K</span>
                    <div class="stat-label">إجمالي المساجد</div>
                    <div class="stat-detail">1,400 جامع</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">20</span>
                    <div class="stat-label">مساجد جديدة</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">5.6M</span>
                    <div class="stat-label">تكلفة الإنشاء</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">310</span>
                    <div class="stat-label">بموارد ثابتة</div>
                    <div class="stat-detail">نمو 8%</div>
                </div>
            </div>
        </div>

        <!-- الإفتاء -->
        <div class="section-card fatwa">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="section-title">قطاع الإفتاء</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">12K+</span>
                    <div class="stat-label">فتاوى صادرة</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">45s</span>
                    <div class="stat-label">متوسط الاستجابة</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">5K+</span>
                    <div class="stat-label">مستخدمي التطبيق</div>
                    <div class="stat-detail">40% خارجيين</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">300</span>
                    <div class="stat-label">مهتدون جدد</div>
                </div>
            </div>
        </div>

        <!-- الوعظ والإرشاد -->
        <div class="section-card guidance">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="section-title">الوعظ والإرشاد</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">800</span>
                    <div class="stat-label">وعاظ</div>
                    <div class="stat-detail">300 رسمي + 500 متطوع</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1.2K+</span>
                    <div class="stat-label">أنشطة وعظية</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">100%</span>
                    <div class="stat-label">تغطية جغرافية</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 100%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">60K+</span>
                    <div class="stat-label">متابعون رقميون</div>
                </div>
            </div>
        </div>

        <!-- التحول الرقمي -->
        <div class="section-card digital">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="section-title">التحول الرقمي</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">73%</span>
                    <div class="stat-label">مستوى التحول</div>
                    <div class="stat-detail">من 53% في 2023</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 73%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">50</span>
                    <div class="stat-label">خدمات إلكترونية</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">136K</span>
                    <div class="stat-label">مستخدمو البوابات</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">85%</span>
                    <div class="stat-label">خدمات محولة</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الشؤون الفلكية -->
        <div class="section-card astronomy">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-moon"></i>
                </div>
                <div class="section-title">الشؤون الفلكية</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">100%</span>
                    <div class="stat-label">دقة التقويم</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 100%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">26</span>
                    <div class="stat-label">مواقع رصد</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">8</span>
                    <div class="stat-label">خبراء فلكيون</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">5</span>
                    <div class="stat-label">فعاليات كبرى</div>
                </div>
            </div>
        </div>

        <!-- الحج والعمرة -->
        <div class="section-card hajj">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-kaaba"></i>
                </div>
                <div class="section-title">الحج والعمرة</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">14K</span>
                    <div class="stat-label">حصة عمان</div>
                    <div class="stat-detail">13,530 مواطن</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">33.5K</span>
                    <div class="stat-label">طلبات إلكترونية</div>
                    <div class="stat-detail">41.7% مقبولة</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">99.6%</span>
                    <div class="stat-label">إكمال الإجراءات</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 99.6%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1,417</span>
                    <div class="stat-label">متوسط التكلفة (براً)</div>
                </div>
            </div>
        </div>

        <!-- أموال الأيتام -->
        <div class="section-card orphan">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-child"></i>
                </div>
                <div class="section-title">أموال الأيتام</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">5K</span>
                    <div class="stat-label">أيتام مكفولون</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">70M</span>
                    <div class="stat-label">ريال مُدارة</div>
                    <div class="stat-detail">عائد 5%</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">3.5M</span>
                    <div class="stat-label">صرف مباشر</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1K</span>
                    <div class="stat-label">برامج تأهيل</div>
                </div>
            </div>
        </div>

        <!-- الموارد البشرية -->
        <div class="section-card hr">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="section-title">الموارد البشرية</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">1K</span>
                    <div class="stat-label">موظفون</div>
                    <div class="stat-detail">98% عمانيين، 30% نساء</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">35</span>
                    <div class="stat-label">برامج تدريب</div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">78%</span>
                    <div class="stat-label">رضا الموظفين</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 78%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-value">2%</span>
                    <div class="stat-label">معدل الدوران</div>
                    <div class="stat-detail">من 5% في 2021</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation for progress bars
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-fill');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const progressBar = entry.target;
                        const width = progressBar.style.width;
                        progressBar.style.width = '0';
                        setTimeout(() => {
                            progressBar.style.width = width;
                        }, 200);
                    }
                });
            });

            progressBars.forEach(bar => {
                observer.observe(bar);
            });
        });

        // Smooth scroll and hover effects
        document.querySelectorAll('.section-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>