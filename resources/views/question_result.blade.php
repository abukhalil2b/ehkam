<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار مصارف الزكاة</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2e7d32;
            --secondary: #4caf50;
            --accent: #ff9800;
            --light: #f8f9fa;
            --dark: #1b5e20;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }
        
        h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .timer-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        
        .timer {
            background-color: white;
            border-radius: 50px;
            padding: 12px 30px;
            box-shadow: var(--shadow);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .timer i {
            color: var(--accent);
        }
        
        .question-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border-right: 5px solid var(--primary);
        }
        
        .question-text {
            font-size: 1.6rem;
            margin-bottom: 25px;
            color: var(--dark);
            font-weight: 600;
            text-align: center;
            line-height: 1.8;
        }
        
        .options-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .options-container {
                grid-template-columns: 1fr;
            }
        }
        
        .option {
            background-color: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .option:hover {
            background-color: #e8f5e9;
            border-color: var(--secondary);
            transform: translateY(-3px);
        }
        
        .option.selected {
            background-color: #e8f5e9;
            border-color: var(--primary);
            box-shadow: 0 4px 8px rgba(46, 125, 50, 0.2);
        }
        
        .option.correct {
            background-color: #e8f5e9;
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .option.incorrect {
            background-color: #ffebee;
            border-color: #f44336;
            color: #c62828;
        }
        
        .option-letter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .option.selected .option-letter {
            background-color: var(--primary);
            color: white;
        }
        
        .option.correct .option-letter {
            background-color: var(--primary);
            color: white;
        }
        
        .option.incorrect .option-letter {
            background-color: #f44336;
            color: white;
        }
        
        .staff-answers {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--dark);
            font-weight: 700;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .staff-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }
        
        .staff-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .staff-card:hover {
            transform: translateY(-3px);
        }
        
        .staff-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .staff-answer {
            font-size: 1.1rem;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
        }
        
        .correct-answer {
            background-color: #e8f5e9;
            color: var(--primary);
        }
        
        .incorrect-answer {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .no-answer {
            background-color: #f5f5f5;
            color: #757575;
        }
        
        .results-section {
            margin-top: 30px;
            text-align: center;
        }
        
        .correct-answer-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            padding: 12px 20px;
            background-color: #e8f5e9;
            border-radius: 10px;
            display: inline-block;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .submit-btn:active {
            transform: translateY(0);
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
            <h1>السؤال: عدد مصارف الزكاة؟</h1>
            <p class="subtitle">اختر الإجابة الصحيحة من بين الخيارات التالية</p>
        </header>
        
        <div class="timer-container">
            <div class="timer">
                <i class="fas fa-clock"></i>
                <span id="time">02:00</span>
            </div>
        </div>
        
        <div class="question-card">
            <div class="question-text">
                كم عدد مصارف الزكاة الشرعية؟
            </div>
            <div class="options-container">
                <div class="option" data-option="a">
                    <div class="option-letter">أ</div>
                    <div class="option-text">سبعة مصارف</div>
                </div>
                <div class="option" data-option="b">
                    <div class="option-letter">ب</div>
                    <div class="option-text">ثمانية مصارف</div>
                </div>
                <div class="option" data-option="c">
                    <div class="option-letter">ج</div>
                    <div class="option-text">تسعة مصارف</div>
                </div>
                <div class="option" data-option="d">
                    <div class="option-letter">د</div>
                    <div class="option-text">عشرة مصارف</div>
                </div>
            </div>
        </div>
        
        <div class="staff-answers">
            <h2 class="section-title">إجابات الموظفين</h2>
            <div class="staff-list" id="staffList">
                <!-- سيتم تعبئة هذا القسم بالبيانات عبر JavaScript -->
            </div>
        </div>
        
        <div class="results-section">
            <div class="correct-answer-text" id="correctAnswerText">
                الإجابة الصحيحة: ثمانية مصارف (ب)
            </div>
            <button class="submit-btn" id="submitBtn">تأكيد الإجابة</button>
        </div>
        
        <footer>
            <p>نظام الاختبارات - قسم التطوير المؤسسي</p>
        </footer>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // بيانات الموظفين وإجاباتهم
        const staffMembers = [
            { id: 1, name: "أحمد", answer: "b", correct: true },
            { id: 2, name: "حمدان", answer: "a", correct: false },
            { id: 3, name: "حميد", answer: "b", correct: true },
            { id: 4, name: "مسعود", answer: "c", correct: false }
        ];
        
        // تهيئة العداد
        let timeLeft = 120; // 2 دقيقة بالثواني
        const timerElement = document.getElementById('time');
        let timerInterval;
        
        // بدء العداد
        function startTimer() {
            timerInterval = setInterval(function() {
                timeLeft--;
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    submitAnswer();
                }
            }, 1000);
        }
        
        // عرض إجابات الموظفين
        function displayStaffAnswers() {
            const staffList = document.getElementById('staffList');
            
            staffList.innerHTML = staffMembers.map(staff => {
                let answerClass = "no-answer";
                let answerText = "لم يجب بعد";
                
                if (staff.answer) {
                    answerClass = staff.correct ? "correct-answer" : "incorrect-answer";
                    
                    // تحويل الإجابة إلى نص
                    switch(staff.answer) {
                        case 'a':
                            answerText = "أ) سبعة مصارف";
                            break;
                        case 'b':
                            answerText = "ب) ثمانية مصارف";
                            break;
                        case 'c':
                            answerText = "ج) تسعة مصارف";
                            break;
                        case 'd':
                            answerText = "د) عشرة مصارف";
                            break;
                    }
                }
                
                return `
                    <div class="staff-card">
                        <div class="staff-name">${staff.name}</div>
                        <div class="staff-answer ${answerClass}">${answerText}</div>
                    </div>
                `;
            }).join('');
        }
        
        // إدارة اختيار الإجابة
        let selectedOption = null;
        
        document.querySelectorAll('.option').forEach(option => {
            option.addEventListener('click', function() {
                // إزالة التحديد من جميع الخيارات
                document.querySelectorAll('.option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // تحديد الخيار الحالي
                this.classList.add('selected');
                selectedOption = this.getAttribute('data-option');
            });
        });
        
        // تأكيد الإجابة
        function submitAnswer() {
            if (!selectedOption) {
                alert("يرجى اختيار إجابة قبل التأكيد");
                return;
            }
            
            // إظهار الإجابة الصحيحة
            document.querySelectorAll('.option').forEach(option => {
                if (option.getAttribute('data-option') === 'b') {
                    option.classList.add('correct');
                } else if (option.getAttribute('data-option') === selectedOption && selectedOption !== 'b') {
                    option.classList.add('incorrect');
                }
            });
            
            // إيقاف العداد
            clearInterval(timerInterval);
            
            // تعطيل زر التأكيد
            document.getElementById('submitBtn').disabled = true;
            
            // تحديث إجابات الموظفين بإضافة المستخدم الحالي
            const currentUserAnswer = {
                id: 5,
                name: "أنت",
                answer: selectedOption,
                correct: selectedOption === 'b'
            };
            
            staffMembers.push(currentUserAnswer);
            displayStaffAnswers();
        }
        
        // إضافة مستمع الحدث لزر التأكيد
        document.getElementById('submitBtn').addEventListener('click', submitAnswer);
        
        // تهيئة الصفحة عند التحميل
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
            displayStaffAnswers();
        });
    </script>
</body>
</html>