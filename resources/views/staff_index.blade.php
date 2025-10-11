<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الموظفين</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

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
            max-width: 1200px;
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

        .featured-employee {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .featured-employee:hover {
            transform: translateY(-5px);
        }

        .employee-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: white;
            font-size: 3.5rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .employee-name {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .employee-position {
            font-size: 1.3rem;
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .employee-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 100%;
            margin-top: 20px;
        }

        .detail-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .detail-card:hover {
            transform: translateY(-3px);
        }

        .detail-card i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .detail-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .employees-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .employee-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .employee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .employee-card-header {
            padding: 20px;
            text-align: center;
            color: white;
        }

        .employee-card.ahmed .employee-card-header {
            background-color: #3498db;
        }

        .employee-card.hamdan .employee-card-header {
            background-color: #2ecc71;
        }

        .employee-card.hameed .employee-card-header {
            background-color: #f39c12;
        }

        .employee-card.masood .employee-card-header {
            background-color: #9b59b6;
        }

        .employee-card-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 2rem;
        }

        .employee-card-name {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .employee-card-position {
            font-size: 1rem;
            opacity: 0.9;
        }

        .employee-card-details {
            padding: 15px;
        }

        .employee-card-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .employee-card-detail:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
        }

        .detail-info {
            font-weight: 600;
            color: #2c3e50;
        }

        .points-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .employee-details {
                grid-template-columns: 1fr;
            }

            .employees-list {
                grid-template-columns: 1fr;
            }

            .featured-employee {
                padding: 20px;
            }

            .employee-avatar {
                width: 120px;
                height: 120px;
                font-size: 2.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="featured-employee" id="featuredEmployee">
            <!-- سيتم تعبئة هذا القسم بالبيانات عبر JavaScript -->
        </section>

        <section class="employees-list" id="employeesList">
            <!-- سيتم تعبئة هذا القسم بالبيانات عبر JavaScript -->
        </section>

        <footer>
            <p>نظام إدارة الموظفين - تم التحديث بتاريخ <span id="currentDate"></span></p>
        </footer>
    </div>

    <script>
        // بيانات الموظفين
        const employees = [{
                id: 1,
                name: "أحمد",
                position: "مدير",
                gender: "ذكر",
                hireDate: "2020-03-15",
                points: 945,
                lastActivity: "2023-10-28",
                color: "#3498db"
            },
            {
                id: 2,
                name: "حمدان",
                position: "المدير المساعد",
                gender: "ذكر",
                hireDate: "2021-07-22",
                points: 820,
                lastActivity: "2023-10-25",
                color: "#2ecc71"
            },
            {
                id: 3,
                name: "حميد",
                position: "رئيس قسم الجودة",
                gender: "ذكر",
                hireDate: "2019-11-05",
                points: 780,
                lastActivity: "2023-10-27",
                color: "#f39c12"
            },
            {
                id: 4,
                name: "مسعود",
                position: "رئيس قسم التخطيط",
                gender: "ذكر",
                hireDate: "2022-01-10",
                points: 650,
                lastActivity: "2023-10-26",
                color: "#9b59b6"
            }
        ];

        // عرض التاريخ الحالي
        const currentDateElement = document.getElementById('currentDate');
        const today = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        currentDateElement.textContent = today.toLocaleDateString('ar-SA', options);

        // عرض الموظف المميز (الأول في القائمة)
        function displayFeaturedEmployee(employee) {
            const featuredSection = document.getElementById('featuredEmployee');

            // حساب مدة العمل
            const hireDate = new Date(employee.hireDate);
            const yearsOfService = today.getFullYear() - hireDate.getFullYear();

            featuredSection.innerHTML = `
                <div class="employee-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="employee-name">${employee.name}</h2>
                <p class="employee-position">${employee.position}</p>
                <div class="employee-details">
                    <div class="detail-card">
                        <i class="fas fa-venus-mars"></i>
                        <div class="detail-title">الجنس</div>
                        <div class="detail-value">${employee.gender}</div>
                    </div>
                    <div class="detail-card">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="detail-title">تاريخ التعيين</div>
                        <div class="detail-value">${formatDate(employee.hireDate)}</div>
                    </div>
                    <div class="detail-card">
                        <i class="fas fa-chart-line"></i>
                        <div class="detail-title">مدة العمل</div>
                        <div class="detail-value">${yearsOfService} سنوات</div>
                    </div>
                    <div class="detail-card">
                        <i class="fas fa-star"></i>
                        <div class="detail-title">النقاط المكتسبة</div>
                        <div class="detail-value">${employee.points} نقطة</div>
                    </div>
                    <div class="detail-card">
                        <i class="fas fa-clock"></i>
                        <div class="detail-title">آخر تفاعل</div>
                        <div class="detail-value">${formatDate(employee.lastActivity)}</div>
                    </div>
                </div>
            `;

            // تعيين لون الخلفية بناءً على لون الموظف
            featuredSection.style.borderTop = `5px solid ${employee.color}`;
        }

        // عرض قائمة الموظفين
        function displayEmployeesList() {
            const employeesListSection = document.getElementById('employeesList');

            employeesListSection.innerHTML = employees.map(employee => {
                // حساب مدة العمل
                const hireDate = new Date(employee.hireDate);
                const yearsOfService = today.getFullYear() - hireDate.getFullYear();

                return `
                    <div class="employee-card ${employee.name === 'أحمد' ? 'ahmed' : employee.name === 'حمدان' ? 'hamdan' : employee.name === 'حميد' ? 'hameed' : 'masood'}" data-id="${employee.id}">
                        <div class="employee-card-header">
                            <div class="employee-card-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3 class="employee-card-name">${employee.name}</h3>
                            <p class="employee-card-position">${employee.position}</p>
                        </div>
                        <div class="employee-card-details">
                            <div class="employee-card-detail">
                                <span class="detail-label">الجنس:</span>
                                <span class="detail-info">${employee.gender}</span>
                            </div>
                            <div class="employee-card-detail">
                                <span class="detail-label">تاريخ التعيين:</span>
                                <span class="detail-info">${formatDate(employee.hireDate)}</span>
                            </div>
                            <div class="employee-card-detail">
                                <span class="detail-label">مدة العمل:</span>
                                <span class="detail-info">${yearsOfService} سنوات</span>
                            </div>
                            <div class="employee-card-detail">
                                <span class="detail-label">النقاط:</span>
                                <span class="detail-info">
                                    <span class="points-badge">${employee.points} نقطة</span>
                                </span>
                            </div>
                            <div class="employee-card-detail">
                                <span class="detail-label">آخر تفاعل:</span>
                                <span class="detail-info">${formatDate(employee.lastActivity)}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // إضافة مستمعات الأحداث لبطاقات الموظفين
            const employeeCards = document.querySelectorAll('.employee-card');
            employeeCards.forEach(card => {
                card.addEventListener('click', function() {
                    const employeeId = parseInt(this.getAttribute('data-id'));
                    const employee = employees.find(emp => emp.id === employeeId);
                    displayFeaturedEmployee(employee);
                });
            });
        }

        // دالة لتهيئة التاريخ
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('ar-SA');
        }

        // تهيئة الصفحة عند التحميل
        document.addEventListener('DOMContentLoaded', function() {
            displayFeaturedEmployee(employees[0]);
            displayEmployeesList();
        });
    </script>
</body>

</html>
