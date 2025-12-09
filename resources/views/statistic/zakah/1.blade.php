<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متابعة إيرادات الزكاة</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .target-summary {
            background-color: #e8f4fd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
            border-right: 5px solid #3498db;
        }
        
        .target-summary h2 {
            color: #2980b9;
            margin-bottom: 10px;
        }
        
        .target-amount {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .quarters-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .quarter {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .quarter:hover {
            transform: translateY(-5px);
        }
        
        .quarter h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .quarter-dates {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        
        .quarter-target {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .quarter-amount {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .quarter-percentage {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .quarter-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        
        .quarter-progress {
            height: 8px;
            background-color: #e0e0e0;
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .quarter-progress-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        
        .quarter-1 {
            border-top: 5px solid #27ae60;
        }
        
        .quarter-1 .quarter-progress-bar {
            background-color: #27ae60;
        }
        
        .quarter-2 {
            border-top: 5px solid #3498db;
        }
        
        .quarter-2 .quarter-progress-bar {
            background-color: #3498db;
        }
        
        .quarter-3 {
            border-top: 5px solid #f39c12;
        }
        
        .quarter-3 .quarter-progress-bar {
            background-color: #f39c12;
        }
        
        .quarter-4 {
            border-top: 5px solid #e74c3c;
        }
        
        .quarter-4 .quarter-progress-bar {
            background-color: #e74c3c;
        }
        
        .status-completed {
            background-color: #d5f4e6;
            color: #27ae60;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-future {
            background-color: #e2e3e5;
            color: #6c757d;
        }
        
        .status-active {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .input-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
        }
        
        .input-section h2 {
            color: #e74c3c;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s;
            display: block;
            margin: 0 auto;
        }
        
        button:hover {
            background-color: #c0392b;
        }
        
        .progress-container {
            margin-top: 30px;
        }
        
        .progress-bar {
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(to right, #27ae60, #2ecc71);
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #666;
        }
        
        .quarter-targets-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .target-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .target-item .label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .target-item .value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .quarters-container {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 15px;
            }
            
            .quarter-targets-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>متابعة إيرادات الزكاة</h1>
            <p>لعام 2023</p>
        </header>
        
        <div class="target-summary">
            <h2>المستهدف السنوي</h2>
            <div class="target-amount">20,000 ريال</div>
        </div>
        
        <div class="quarters-container">
            <div class="quarter quarter-1">
                <h3>الربع الأول</h3>
                <div class="quarter-dates">١ يناير - ٣١ مارس</div>
                <div class="quarter-target">المستهدف: 5,000 ريال</div>
                <div class="quarter-amount" id="q1-amount">4,500 ريال</div>
                <div class="quarter-percentage" id="q1-percentage">90%</div>
                <div class="quarter-progress">
                    <div class="quarter-progress-bar" id="q1-progress" style="width: 90%"></div>
                </div>
                <div class="quarter-status status-completed">تم التحقيق</div>
            </div>
            
            <div class="quarter quarter-2">
                <h3>الربع الثاني</h3>
                <div class="quarter-dates">١ أبريل - ٣٠ يونيو</div>
                <div class="quarter-target">المستهدف: 5,000 ريال</div>
                <div class="quarter-amount" id="q2-amount">4,200 ريال</div>
                <div class="quarter-percentage" id="q2-percentage">84%</div>
                <div class="quarter-progress">
                    <div class="quarter-progress-bar" id="q2-progress" style="width: 84%"></div>
                </div>
                <div class="quarter-status status-completed">تم التحقيق</div>
            </div>
            
            <div class="quarter quarter-3">
                <h3>الربع الثالث</h3>
                <div class="quarter-dates">١ يوليو - ٣٠ سبتمبر</div>
                <div class="quarter-target">المستهدف: 5,000 ريال</div>
                <div class="quarter-amount" id="q3-amount">4,800 ريال</div>
                <div class="quarter-percentage" id="q3-percentage">96%</div>
                <div class="quarter-progress">
                    <div class="quarter-progress-bar" id="q3-progress" style="width: 96%"></div>
                </div>
                <div class="quarter-status status-completed">تم التحقيق</div>
            </div>
            
            <div class="quarter quarter-4">
                <h3>الربع الرابع</h3>
                <div class="quarter-dates">١ أكتوبر - ٣١ ديسمبر</div>
                <div class="quarter-target">المستهدف: 5,000 ريال</div>
                <div class="quarter-amount" id="q4-amount">0 ريال</div>
                <div class="quarter-percentage" id="q4-percentage">0%</div>
                <div class="quarter-progress">
                    <div class="quarter-progress-bar" id="q4-progress" style="width: 0%"></div>
                </div>
                <div class="quarter-status status-active">قيد الإدخال</div>
            </div>
        </div>
        
        <div class="quarter-targets-summary">
            <div class="target-item">
                <div class="label">إجمالي المحقق</div>
                <div class="value" id="total-achieved">13,500 ريال</div>
            </div>
            <div class="target-item">
                <div class="label">المتبقي للهدف</div>
                <div class="value" id="remaining-target">6,500 ريال</div>
            </div>
            <div class="target-item">
                <div class="label">نسبة الإنجاز</div>
                <div class="value" id="overall-percentage">67.5%</div>
            </div>
        </div>
        
        <div class="input-section">
            <h2>إدخال إيرادات الربع الرابع</h2>
            <div class="form-group">
                <label for="q4-input">المبلغ المحقق في الربع الرابع (ريال)</label>
                <input type="number" id="q4-input" min="0" max="20000" placeholder="أدخل المبلغ المحقق">
            </div>
            <button id="submit-btn">تحديث البيانات</button>
        </div>
        
        <div class="progress-container">
            <h3>التقدم نحو تحقيق الهدف السنوي</h3>
            <div class="progress-bar">
                <div class="progress" id="annual-progress" style="width: 67.5%"></div>
            </div>
            <div class="progress-labels">
                <span>0%</span>
                <span id="progress-percentage">67.5%</span>
                <span>100%</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // البيانات الأولية
            const quarterTargets = {
                q1: 5000,
                q2: 5000,
                q3: 5000,
                q4: 5000
            };
            
            const quarterAchieved = {
                q1: 4500,
                q2: 4200,
                q3: 4800,
                q4: 0
            };
            
            const q4Input = document.getElementById('q4-input');
            const submitBtn = document.getElementById('submit-btn');
            
            // تحديث جميع البيانات المعروضة
            function updateAllData() {
                // تحديث قيم الأرباع
                document.getElementById('q1-amount').textContent = formatNumber(quarterAchieved.q1) + ' ريال';
                document.getElementById('q2-amount').textContent = formatNumber(quarterAchieved.q2) + ' ريال';
                document.getElementById('q3-amount').textContent = formatNumber(quarterAchieved.q3) + ' ريال';
                document.getElementById('q4-amount').textContent = formatNumber(quarterAchieved.q4) + ' ريال';
                
                // تحديث النسب المئوية للأرباع
                updateQuarterPercentage('q1');
                updateQuarterPercentage('q2');
                updateQuarterPercentage('q3');
                updateQuarterPercentage('q4');
                
                // تحديث الإجماليات
                updateTotals();
            }
            
            // تحديث النسبة المئوية لربع معين
            function updateQuarterPercentage(quarter) {
                const percentage = (quarterAchieved[quarter] / quarterTargets[quarter]) * 100;
                const percentageElement = document.getElementById(`${quarter}-percentage`);
                const progressBar = document.getElementById(`${quarter}-progress`);
                
                percentageElement.textContent = Math.min(percentage, 100).toFixed(0) + '%';
                progressBar.style.width = Math.min(percentage, 100) + '%';
            }
            
            // تحديث الإجماليات
            function updateTotals() {
                const totalAchieved = quarterAchieved.q1 + quarterAchieved.q2 + quarterAchieved.q3 + quarterAchieved.q4;
                const remaining = 20000 - totalAchieved;
                const overallPercentage = (totalAchieved / 20000) * 100;
                
                document.getElementById('total-achieved').textContent = formatNumber(totalAchieved) + ' ريال';
                document.getElementById('remaining-target').textContent = formatNumber(remaining) + ' ريال';
                document.getElementById('overall-percentage').textContent = overallPercentage.toFixed(1) + '%';
                
                // تحديث شريط التقدم السنوي
                document.getElementById('annual-progress').style.width = Math.min(overallPercentage, 100) + '%';
                document.getElementById('progress-percentage').textContent = overallPercentage.toFixed(1) + '%';
            }
            
            // تنسيق الأرقام بفواصل
            function formatNumber(num) {
                return num.toLocaleString();
            }
            
            // تحديث العرض بناءً على إدخال الربع الرابع
            submitBtn.addEventListener('click', function() {
                const q4Value = parseFloat(q4Input.value) || 0;
                
                if (q4Value < 0) {
                    alert('يرجى إدخال قيمة صحيحة للمبلغ المحقق');
                    return;
                }
                
                // تحديث قيمة الربع الرابع
                quarterAchieved.q4 = q4Value;
                
                // تحديث جميع البيانات
                updateAllData();
                
                // إظهار رسالة نجاح
                alert('تم تحديث بيانات الربع الرابع بنجاح!');
            });
            
            // تهيئة البيانات عند تحميل الصفحة
            updateAllData();
        });
    </script>
</body>
</html>