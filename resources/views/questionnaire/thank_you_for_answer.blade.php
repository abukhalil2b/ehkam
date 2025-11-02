<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الحضور في الورشة</title>
    <!-- Tailwind CSS CDN for quick setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts - Ensure Tajawal is loaded -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f7f9fb 0%, #eef2f7 100%);
            min-height: 100vh;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 640px) {

            .attendance-table th,
            .attendance-table td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-blue-800 text-white py-3 shadow-lg">
        <div class="max-w-4xl mx-auto px-2 text-center">
            <h1 class="text-2xl font-extrabold">
                المديرية العامة للتخطيط والدراسات
            </h1>
            <h2 class="text-xl font-extrabold"> وزارة الأوقاف والشؤون الدينية</h2>
        </div>
    </div>
    <div class="max-w-6xl mx-auto p-4">
        <div class="bg-white rounded p-6 text-3xl text-center">
            تم إستلام الإجابات،، شكرا لك.
        </div>
    </div>

</body>

</html>
