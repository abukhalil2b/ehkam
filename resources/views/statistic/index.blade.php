<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إحصائية وزارة الأوقاف والشؤون الدينية</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        h2 {
            color: #2980b9;
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            margin-top: 30px;
        }

        .section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .stat-value {
            font-size: 1.4em;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .stat-details {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .highlight {
            background-color: #fffde7;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .percentage-bar {
            height: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            margin-top: 10px;
            overflow: hidden;
        }

        .percentage-fill {
            height: 100%;
            background-color: #3498db;
            width: 0%;
            transition: width 1s;
        }

        .note {
            font-style: italic;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <h1>إحصائية وزارة الأوقاف والشؤون الدينية - 2024</h1>

    <div class="flex justify-between">
        <div class="section w-full">
            <a href="{{ route('statistic.quran') }}">
                <h2>قطاع القرآن الكريم</h2>
            </a>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">عدد مدارس تعليم القرآن</div>
                    <div class="stat-value">1,645</div>
                    <div class="stat-details">مدارس متكاملة، حلقات في الجوامع، مجالس لتحفيظ القرآن</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">عدد الدارسين</div>
                    <div class="stat-value">100,000</div>
                    <div class="stat-details">84% منهم من الإناث</div>
                    <div class="percentage-bar">
                        <div class="percentage-fill" style="width: 84%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">عدد المعلمين والمعلمات</div>
                    <div class="stat-value">1,360</div>
                    <div class="stat-details">97% منهم من النساء</div>
                    <div class="percentage-bar">
                        <div class="percentage-fill" style="width: 97%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">البرنامج الإلكتروني</div>
                    <div class="stat-value">1,600</div>
                    <div class="stat-details">طالب مسجل في برنامج تعليم القرآن عن بُعد</div>
                </div>
            </div>
        </div>

        <div class="section w-full">
            <h2>قطاع الأوقاف</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">الأصول الوقفية المسجلة</div>
                    <div class="stat-value">39,000+</div>
                    <div class="stat-details">49% منها عبارة عن مبانٍ وعقارات ثابتة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">مصارف ريع الأوقاف</div>
                    <div class="stat-value">82%</div>
                    <div class="stat-details">موجهة للجوامع والمساجد</div>
                    <div class="percentage-bar">
                        <div class="percentage-fill" style="width: 82%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">المؤسسات الوقفية</div>
                    <div class="stat-value">35</div>
                    <div class="stat-details">73% من الكيانات الوقفية هي مؤسسات وقفية عامة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">العوائد المتوقعة</div>
                    <div class="stat-value">2.5 مليون ريال</div>
                    <div class="stat-details">عائد استثماري متوقع لمشروعين وقفيين لرعاية الأيتام</div>
                </div>
            </div>
        </div>

    </div>
    
    <div class="section ">
        <h2>قطاع الزكاة</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">إجمالي الزكاة المحصلة والموزعة</div>
                <div class="stat-value">13.84 مليون ريال</div>
                <div class="stat-details">زيادة عن العام الماضي</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">عدد الأسر المستفيدة</div>
                <div class="stat-value">50,000+</div>
                <div class="stat-details">تشمل الأرامل والأيتام والأسر ذات الدخل المحدود</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">عدد لجان الزكاة المحلية</div>
                <div class="stat-value">67</div>
                <div class="stat-details">في مختلف المحافظات</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">التحويلات الإلكترونية للزكاة</div>
                <div class="stat-value">7.17 مليون ريال</div>
                <div class="stat-details">تشكل أكثر من 50% من إجمالي الزكاة المحصلة</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع المساجد والجوامع</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">إجمالي المساجد والجوامع</div>
                <div class="stat-value">17,000</div>
                <div class="stat-details">منها 1,400 جامع</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">المساجد المبنية أو المعاد إعمارها</div>
                <div class="stat-value">20</div>
                <div class="stat-details">5 بتمويل حكومي، 10 بتمويل أهلي، 5 بتمويل مشترك</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">تكلفة الإنشاء والصيانة</div>
                <div class="stat-value">5.6 مليون ريال</div>
                <div class="stat-details">2.35 مليون للبناء الجديد، 3.28 مليون لإعادة الإعمار</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">المساجد ذات الموارد الثابتة</div>
                <div class="stat-value">310</div>
                <div class="stat-details">بنسبة نمو 8% عن العام السابق</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع الإفتاء</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">عدد الفتاوى الصادرة</div>
                <div class="stat-value">12,000+</div>
                <div class="stat-details">60% مسائل عبادات، 20% أحوال شخصية، 20% معاملات مالية</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">متوسط زمن الاستجابة</div>
                <div class="stat-value">45 ثانية</div>
                <div class="stat-details">عند الاتصال بالخط المجاني</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">مستخدمي التطبيق الجديد</div>
                <div class="stat-value">5,000+</div>
                <div class="stat-details">40% منهم من خارج السلطنة</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">المهتدون الجدد</div>
                <div class="stat-value">300</div>
                <div class="stat-details">من جنسيات مختلفة في السلطنة</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع الوعظ والإرشاد</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">عدد الوعاظ الرسميين</div>
                <div class="stat-value">300</div>
                <div class="stat-details">بالإضافة إلى 500 متطوع</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">الأنشطة الوعظية</div>
                <div class="stat-value">1,200+</div>
                <div class="stat-details">محاضرة ودرس ديني</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">التغطية الجغرافية</div>
                <div class="stat-value">100%</div>
                <div class="stat-details">من الولايات</div>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: 100%"></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">المتابعون على وسائل التواصل</div>
                <div class="stat-value">60,000+</div>
                <div class="stat-details">عبر مختلف المنصات</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع التحول الرقمي والتقنية</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">مستوى التحول الرقمي</div>
                <div class="stat-value">73%</div>
                <div class="stat-details">زيادة من 53% في 2023</div>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: 73%"></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">عدد الخدمات الإلكترونية</div>
                <div class="stat-value">50</div>
                <div class="stat-details">خدمة رئيسية متاحة</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">مستخدمي البوابات الإلكترونية</div>
                <div class="stat-value">136,000</div>
                <div class="stat-details">في بوابة خدمات الزكاة</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">نسبة التحول الإلكتروني</div>
                <div class="stat-value">85%</div>
                <div class="stat-details">من خدمات الوزارة</div>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع الشؤون الفلكية</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">دقة التقويم الهجري</div>
                <div class="stat-value">100%</div>
                <div class="stat-details">تطابق بين التوقعات والرؤية البصرية</div>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: 100%"></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">مواقع الرصد الميداني</div>
                <div class="stat-value">26</div>
                <div class="stat-details">موقع مختلف على الأقل خلال السنة</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">الخبراء الفلكيون</div>
                <div class="stat-value">8</div>
                <div class="stat-details">خبراء فلكيين متفرغين</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">الفعاليات الفلكية</div>
                <div class="stat-value">5</div>
                <div class="stat-details">معارض/فعاليات كبرى</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع شؤون الحج والعمرة</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">حصة سلطنة عمان من الحجاج</div>
                <div class="stat-value">14,000</div>
                <div class="stat-details">13,530 مواطن عماني و470 مقيم</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">طلبات التسجيل الإلكترونية</div>
                <div class="stat-value">33,536</div>
                <div class="stat-details">تم قبول 41.7% منها</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">إكمال الإجراءات</div>
                <div class="stat-value">99.6%</div>
                <div class="stat-details">من المقبولين</div>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: 99.6%"></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">متوسط تكاليف الحج</div>
                <div class="stat-value">1,417 - 2,063 ريال</div>
                <div class="stat-details">براً وجواً على التوالي</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع إدارة أموال الأيتام والقُصَّر</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">عدد الأيتام المكفولين</div>
                <div class="stat-value">5,000</div>
                <div class="stat-details">على مستوى السلطنة</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">حجم الأموال المُدارة</div>
                <div class="stat-value">70 مليون ريال</div>
                <div class="stat-details">بمعدل عائد سنوي 5%</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">الصرف المباشر على الأيتام</div>
                <div class="stat-value">3.5 مليون ريال</div>
                <div class="stat-details">نفقات معيشية وتعليمية وصحية</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">برامج التأهيل</div>
                <div class="stat-value">1,000</div>
                <div class="stat-details">يتيم مستهدف في الفئة العمرية 16-18 سنة</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>قطاع تمكين الموارد البشرية</h2>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">عدد الموظفين</div>
                <div class="stat-value">1,000</div>
                <div class="stat-details">98% عمانيين، 30% نساء</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">برامج التدريب</div>
                <div class="stat-value">35</div>
                <div class="stat-details">برنامج تدريبي مختلف</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">رضا الموظفين</div>
                <div class="stat-value">78%</div>
                <div class="stat-details">متوسط نسبة الرضا العام</div>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: 78%"></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">معدل دوران الموظفين</div>
                <div class="stat-value">2%</div>
                <div class="stat-details">انخفاض من 5% في 2021</div>
            </div>
        </div>
    </div>

    <script>
        // Simple animation for percentage bars
        document.addEventListener('DOMContentLoaded', function() {
            const percentageBars = document.querySelectorAll('.percentage-fill');
            percentageBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>

</html>
