{{-- resources/views/meeting_minute/attendance_sheet_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قائمة الحضور - {{ $meetingMinute->title }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; }
        .subtitle { font-size: 18px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .signature-cell { height: 100px; }
        .empty-row td { height: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">قائمة الحضور</div>
        <div class="subtitle">{{ $meetingMinute->title }}</div>
        <div>تاريخ الاجتماع: {{ $meetingMinute->date?->format('Y-m-d') }}</div>
        <div>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">م</th>
                <th width="30%">الاسم</th>
                <th width="30%">التوقيع</th>
                <th width="15%">تاريخ التوقيع</th>
                <th width="15%">وقت التوقيع</th>
            </tr>
        </thead>
        <tbody>
            @forelse($meetingMinute->attendances->whereNotNull('signed_at') as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->name }}</td>
                <td class="signature-cell">
                    @if($attendance->signature && Storage::disk('public')->exists($attendance->signature))
                        <img src="data:image/png;base64,{{ base64_encode(Storage::disk('public')->get($attendance->signature)) }}" 
                             style="max-height: 80px; max-width: 150px;">
                    @else
                        ---
                    @endif
                </td>
                <td>{{ $attendance->signed_at?->format('Y-m-d') }}</td>
                <td>{{ $attendance->signed_at?->format('H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">لا توجد توقيعات مسجلة بعد</td>
            </tr>
            @endforelse
            
            {{-- Add empty rows for manual signatures --}}
            @for($i = max($meetingMinute->attendances->count(), 10); $i < 20; $i++)
            <tr class="empty-row">
                <td>{{ $i + 1 }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <div style="float: left; width: 30%; text-align: center;">
            <div>اسم الكاتب: {{ $meetingMinute->writtenBy->name ?? '---' }}</div>
            <div style="margin-top: 20px;">التوقيع: ________________</div>
        </div>
    </div>
</body>
</html>