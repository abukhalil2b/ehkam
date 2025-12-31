<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تسجيل حضور الاجتماع</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: "Tajawal", Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 720px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            font-size: 22px;
            text-align: center;
        }

        .meta {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .qr {
            text-align: center;
            margin: 20px 0;
        }

        .qr svg {
            max-width: 100%;
        }

        form {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .attendees {
            margin-top: 30px;
        }

        .attendees h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .attendees ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .attendees li {
            background: #f1f5f9;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .footer-note {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>{{ $meetingMinute->title }}</h1>

        <div class="meta">
            @if ($meetingMinute->date)
                تاريخ الاجتماع: {{ $meetingMinute->date->format('Y-m-d') }}
            @endif
        </div>

        <div class="qr">
            {!! $qrCode !!}
            <div style="font-size: 12px; color: #666; margin-top: 6px;">
                امسح الرمز لتسجيل الحضور
            </div>
        </div>

        {{-- Messages --}}
        @if ($errors->any())
            <div class="errors">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Attendance Form --}}
        <form method="POST"
            action="{{ route('meeting_minute.attendance_registration_store', $meetingMinute->public_token) }}">
            @csrf

            <input type="text" name="name" placeholder="الاسم الكامل" required autocomplete="off">

            <button type="submit">
                تسجيل الحضور
            </button>
        </form>

        {{-- Already Registered Attendees --}}
        <div class="attendees">
            <h3>المسجلون بالحضور ({{ count($attendances) }})</h3>

            @if (count($attendances))
                <ul>
                    @foreach ($attendances as $name)
                        <li>{{ $name }}</li>
                    @endforeach
                </ul>
            @else
                <div style="color:#777;font-size:14px;">
                    لم يتم تسجيل أي حضور بعد
                </div>
            @endif
        </div>

        <div class="footer-note">
            يتم تسجيل عنوان IP تلقائياً لأغراض التحقق فقط
        </div>

    </div>

</body>

</html>
