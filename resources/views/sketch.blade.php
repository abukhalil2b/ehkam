<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خريطة ذهنية لسير الإجراءات في المعاملات الحكومية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background-color: #f5f9ff;
            color: #333;
            line-height: 1.5;
            padding: 15px;
            min-height: 100vh;
            font-size: 11px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #1a5fb4 0%, #2d87c8 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            font-size: 1.6rem;
            margin-bottom: 8px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            font-weight: 700;
        }
        
        .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
            font-weight: 300;
        }
        
        .mind-map {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            margin: 25px 0;
        }
        
        .process-flow {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 18px;
            margin-top: 15px;
        }
        
        .step {
            background-color: white;
            border-radius: 10px;
            padding: 18px;
            width: 280px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            position: relative;
            border-top: 5px solid #1a5fb4;
        }
        
        .step:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .step-sector {
            border-top-color: #1a5fb4;
        }
        
        .step-planning {
            border-top-color: #26a269;
        }
        
        .step-number {
            position: absolute;
            top: -12px;
            right: 15px;
            background-color: #1a5fb4;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.95rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .step-sector .step-number {
            background-color: #1a5fb4;
        }
        
        .step-planning .step-number {
            background-color: #26a269;
        }
        
        .step-title {
            font-size: 1.1rem;
            color: #1a5fb4;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        
        .step-planning .step-title {
            color: #26a269;
        }
        
        .step p {
            font-size: 0.85rem;
            margin-bottom: 10px;
            font-weight: 400;
        }
        
        .step-body {
            margin-top: 12px;
        }
        
        .step-body h4 {
            color: #555;
            margin-bottom: 6px;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .step-body ul {
            list-style-type: none;
            padding-right: 12px;
        }
        
        .step-body li {
            margin-bottom: 6px;
            padding-right: 8px;
            position: relative;
            font-size: 0.8rem;
            font-weight: 400;
        }
        
        .step-body li:before {
            content: "•";
            color: #1a5fb4;
            font-weight: bold;
            font-size: 1rem;
            position: absolute;
            right: -12px;
        }
        
        .step-planning .step-body li:before {
            color: #26a269;
        }
        
        .step-connector {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 12px 0;
            color: #888;
            font-size: 1.4rem;
        }
        
        .decision-point {
            background-color: #fff8e1;
            border: 2px dashed #ffb300;
            margin-top: 12px;
            padding: 10px;
            border-radius: 6px;
        }
        
        .decision-point h4 {
            color: #ff8f00;
            margin-bottom: 6px;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .decision-options {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
        }
        
        .option {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.8rem;
        }
        
        .option.reject {
            background-color: #ffebee;
            color: #d32f2f;
        }
        
        .option.approve {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            flex-wrap: wrap;
            font-size: 0.85rem;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }
        
        .sector-color {
            background-color: #1a5fb4;
        }
        
        .planning-color {
            background-color: #26a269;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 0.8rem;
        }
        
        .controls {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin: 15px 0;
        }
        
        .control-btn {
            padding: 8px 16px;
            background-color: #1a5fb4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            font-size: 0.85rem;
        }
        
        .control-btn:hover {
            background-color: #0d4a9e;
        }
        
        .control-btn.reset {
            background-color: #666;
        }
        
        .control-btn.reset:hover {
            background-color: #555;
        }
        
        @media (max-width: 1100px) {
            .process-flow {
                flex-direction: column;
                align-items: center;
            }
            
            .step-connector {
                transform: rotate(90deg);
                height: 30px;
                margin: 5px 0;
            }
        }
        
        @media (max-width: 768px) {
            body {
                font-size: 10px;
                padding: 10px;
            }
            
            h1 {
                font-size: 1.3rem;
            }
            
            .subtitle {
                font-size: 0.8rem;
            }
            
            .step {
                width: 100%;
                max-width: 350px;
                padding: 15px;
            }
            
            .step-title {
                font-size: 1rem;
            }
            
            .legend {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                font-size: 0.8rem;
            }
        }
        
        .active-step {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06); }
            50% { box-shadow: 0 5px 15px rgba(26, 95, 180, 0.2); }
            100% { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06); }
        }
        
        /* إضافة مزيد من التخصيص للخط الصغير */
        .small-text {
            font-size: 0.75rem;
            line-height: 1.4;
        }
        
        .tiny-text {
            font-size: 0.7rem;
            line-height: 1.3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-project-diagram"></i> خريطة ذهنية لسير الإجراءات في المعاملات الحكومية</h1>
            <p class="subtitle tiny-text">تتبع خطوات سير إجراءات المعاملات الحكومية بدءاً من تقديم الطلب حتى إعداد التقرير النهائي</p>
        </header>
        
        <div class="controls">
            <button class="control-btn" id="startTour"><i class="fas fa-play-circle"></i> بدء الجولة التعريفية</button>
            <button class="control-btn reset" id="resetTour"><i class="fas fa-redo"></i> إعادة التعيين</button>
        </div>
        
        <div class="mind-map">
            <div class="process-flow" id="processFlow">
                <!-- الخطوة 1 -->
                <div class="step step-sector" id="step1">
                    <div class="step-number">١</div>
                    <h3 class="step-title"><i class="fas fa-user-edit"></i> مقدم الطلب (القطاع)</h3>
                    <p class="small-text">نوع الطلب: اقتراح مبادرة أو مشروع</p>
                    <div class="step-body">
                        <h4>معلومات المشروع:</h4>
                        <ul class="tiny-text">
                            <li>اسم المشروع</li>
                            <li>وصف المشروع</li>
                            <li>المستفيدين من المشروع</li>
                            <li>نطاق التنفيذ</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 2 -->
                <div class="step step-planning" id="step2">
                    <div class="step-number">٢</div>
                    <h3 class="step-title"><i class="fas fa-search"></i> مستلم الطلب (التخطيط)</h3>
                    <p class="small-text">مراجعة ودراسة المشروع المقترح</p>
                    <div class="decision-point">
                        <h4>قرار التخطيط:</h4>
                        <div class="decision-options">
                            <div class="option reject tiny-text"><i class="fas fa-times-circle"></i> رفض المشروع</div>
                            <div class="option approve tiny-text"><i class="fas fa-check-circle"></i> موافقة على المشروع</div>
                        </div>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 3 -->
                <div class="step step-sector" id="step3">
                    <div class="step-number">٣</div>
                    <h3 class="step-title"><i class="fas fa-file-signature"></i> كتابة الخطة التفصيلية (القطاع)</h3>
                    <p class="small-text">بعد الموافقة على المشروع، يبدأ القطاع في إعداد الخطة التفصيلية للمشروع</p>
                    <div class="step-body">
                        <h4>تشمل الخطة التفصيلية:</h4>
                        <ul class="tiny-text">
                            <li>تفاصيل التنفيذ</li>
                            <li>الجدول الزمني</li>
                            <li>المسؤوليات والمهام</li>
                            <li>المؤشرات والأهداف</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 4 -->
                <div class="step step-planning" id="step4">
                    <div class="step-number">٤</div>
                    <h3 class="step-title"><i class="fas fa-clipboard-check"></i> مراجعة الخطة التفصيلية (التخطيط)</h3>
                    <p class="small-text">مراجعة الخطة التفصيلية للتأكد من:</p>
                    <div class="step-body">
                        <ul class="tiny-text">
                            <li>ملاءمتها للأهداف المحددة</li>
                            <li>واقعية الجدول الزمني</li>
                            <li>وضوح المسؤوليات والمهام</li>
                            <li>شمولية المؤشرات والأهداف</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 5 -->
                <div class="step" id="step5">
                    <div class="step-number">٥</div>
                    <h3 class="step-title"><i class="fas fa-file-invoice-dollar"></i> الاعتماد المالي والتدقيق</h3>
                    <p class="small-text">فحص وتدقيق الجوانب المالية للمشروع والموافقة على الميزانية</p>
                    <div class="step-body">
                        <h4>تشمل عملية الاعتماد المالي:</h4>
                        <ul class="tiny-text">
                            <li>مراجعة الميزانية المقترحة</li>
                            <li>التأكد من توافق التكاليف مع الأنظمة</li>
                            <li>اعتماد الصرفيات المالية</li>
                            <li>التدقيق المالي للمشروع</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 6 -->
                <div class="step step-planning" id="step6">
                    <div class="step-number">٦</div>
                    <h3 class="step-title"><i class="fas fa-tasks"></i> إصدار الموافقة النهائية (التخطيط)</h3>
                    <p class="small-text">بعد الاعتماد المالي، تقوم إدارة التخطيط بإصدار الموافقة النهائية لبدء التنفيذ</p>
                    <div class="step-body">
                        <h4>تشمل الموافقة النهائية:</h4>
                        <ul class="tiny-text">
                            <li>وثيقة اعتماد المشروع</li>
                            <li>تفويض الصلاحيات</li>
                            <li>الجدول الزمني المعتمد</li>
                            <li>الميزانية النهائية</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 7 -->
                <div class="step step-sector" id="step7">
                    <div class="step-number">٧</div>
                    <h3 class="step-title"><i class="fas fa-hard-hat"></i> التنفيذ والمتابعة (القطاع المنفذ)</h3>
                    <p class="small-text">بدء تنفيذ المشروع وإرسال التقارير الدورية والأدلة الداعمة</p>
                    <div class="step-body">
                        <h4>مهام القطاع المنفذ:</h4>
                        <ul class="tiny-text">
                            <li>تنفيذ المشروع حسب الخطة</li>
                            <li>إعداد تقارير المتابعة الدورية</li>
                            <li>توثيق الأدلة الداعمة للتنفيذ</li>
                            <li>معالجة التحديات والمخاطر</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 8 -->
                <div class="step step-planning" id="step8">
                    <div class="step-number">٨</div>
                    <h3 class="step-title"><i class="fas fa-chart-line"></i> المتابعة والتقييم (التخطيط)</h3>
                    <p class="small-text">استلام التقارير والأدلة الداعمة وتقييم سير التنفيذ</p>
                    <div class="step-body">
                        <h4>أنشطة المتابعة:</h4>
                        <ul class="tiny-text">
                            <li>مراجعة التقارير الدورية</li>
                            <li>فحص الأدلة الداعمة</li>
                            <li>مقارنة الإنجاز مع المخطط</li>
                            <li>تقديم التغذية الراجعة للقطاع المنفذ</li>
                        </ul>
                    </div>
                </div>
                
                <div class="step-connector">
                    <i class="fas fa-arrow-left"></i>
                </div>
                
                <!-- الخطوة 9 -->
                <div class="step step-planning" id="step9">
                    <div class="step-number">٩</div>
                    <h3 class="step-title"><i class="fas fa-flag-checkered"></i> التقرير النهائي (التخطيط)</h3>
                    <p class="small-text">إعداد التقرير النهائي للمشروع وإرساله للجهات الخارجية المعنية</p>
                    <div class="step-body">
                        <h4>محتوى التقرير النهائي:</h4>
                        <ul class="tiny-text">
                            <li>ملخص تنفيذ المشروع</li>
                            <li>النتائج والإنجازات</li>
                            <li>التحديات والحلول</li>
                            <li>الدروس المستفادة</li>
                            <li>التوصيات للمشاريع المستقبلية</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color sector-color"></div>
                    <span class="tiny-text">إجراءات القطاع</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color planning-color"></div>
                    <span class="tiny-text">إجراءات التخطيط</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #fff8e1; border: 1px solid #ffb300;"></div>
                    <span class="tiny-text">نقطة قرار</span>
                </div>
                <div class="legend-item">
                    <i class="fas fa-arrow-left" style="color: #888; font-size: 0.9rem;"></i>
                    <span class="tiny-text">اتجاه سير الإجراءات</span>
                </div>
            </div>
        </div>
        
        <footer class="tiny-text">
            <p>خريطة سير الإجراءات في المعاملات الحكومية - تم تصميمها باستخدام HTML و CSS و JavaScript</p>
            <p>توضح الخريطة التدفق التسلسلي لمعالجة المشاريع والمبادرات الحكومية</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const startTourBtn = document.getElementById('startTour');
            const resetTourBtn = document.getElementById('resetTour');
            let currentStep = 0;
            let tourInterval;
            
            // بدء الجولة التعريفية
            startTourBtn.addEventListener('click', function() {
                resetTour();
                startTour();
            });
            
            // إعادة تعيين الجولة
            resetTourBtn.addEventListener('click', function() {
                resetTour();
            });
            
            // بدء الجولة خطوة بخطوة
            function startTour() {
                if (tourInterval) clearInterval(tourInterval);
                
                // تفعيل الخطوة الأولى
                activateStep(currentStep);
                
                // الانتقال التلقائي بين الخطوات كل 3 ثواني
                tourInterval = setInterval(function() {
                    deactivateStep(currentStep);
                    currentStep++;
                    
                    if (currentStep >= steps.length) {
                        clearInterval(tourInterval);
                        currentStep = 0;
                        startTourBtn.textContent = 'بدء الجولة مرة أخرى';
                        return;
                    }
                    
                    activateStep(currentStep);
                }, 2500);
                
                startTourBtn.textContent = 'جولة قيد التشغيل...';
            }
            
            // تفعيل خطوة معينة
            function activateStep(stepIndex) {
                if (stepIndex >= 0 && stepIndex < steps.length) {
                    const step = steps[stepIndex];
                    step.classList.add('active-step');
                    
                    // تمرير العرض إلى الخطوة الحالية
                    step.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'center'
                    });
                }
            }
            
            // إلغاء تفعيل خطوة معينة
            function deactivateStep(stepIndex) {
                if (stepIndex >= 0 && stepIndex < steps.length) {
                    steps[stepIndex].classList.remove('active-step');
                }
            }
            
            // إعادة تعيين الجولة
            function resetTour() {
                if (tourInterval) clearInterval(tourInterval);
                
                // إلغاء تفعيل جميع الخطوات
                steps.forEach(step => {
                    step.classList.remove('active-step');
                });
                
                currentStep = 0;
                startTourBtn.textContent = 'بدء الجولة التعريفية';
                
                // العودة إلى بداية الصفحة
                document.querySelector('.process-flow').scrollIntoView({
                    behavior: 'smooth'
                });
            }
            
            // إضافة تأثير عند النقر على أي خطوة
            steps.forEach(step => {
                step.addEventListener('click', function() {
                    // إزالة التأثير من جميع الخطوات
                    steps.forEach(s => s.classList.remove('active-step'));
                    // إضافة التأثير للخطوة المختارة
                    this.classList.add('active-step');
                    
                    // إيقاف الجولة التلقائية إذا كانت تعمل
                    if (tourInterval) {
                        clearInterval(tourInterval);
                        startTourBtn.textContent = 'بدء الجولة التعريفية';
                    }
                });
            });
            
            // بدء الجولة تلقائياً بعد تحميل الصفحة
            setTimeout(startTour, 800);
        });
    </script>
</body>
</html>