<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير تحليل SWOT - {{ $project->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

        @font-face {
            font-family: 'Cairo';
            font-style: normal;
            font-weight: 400;
            src: url({{ storage_path('fonts/cairo-regular.ttf') }}) format('truetype');
        }

        @font-face {
            font-family: 'Cairo';
            font-style: normal;
            font-weight: 700;
            src: url({{ storage_path('fonts/cairo-bold.ttf') }}) format('truetype');
        }


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 20px;
            direction: rtl;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }

        .header h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header .subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-right: 4px solid #3b82f6;
        }

        .meta-item {
            text-align: center;
        }

        .meta-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .meta-value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            background: #3b82f6;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
        }

        .swot-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .swot-section {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .swot-header {
            padding: 12px;
            color: white;
            font-weight: 600;
            text-align: center;
        }

        .swot-content {
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .swot-item {
            padding: 10px;
            margin-bottom: 8px;
            background: #f9fafb;
            border-radius: 6px;
            border-right: 3px solid #9ca3af;
        }

        .swot-item-text {
            font-size: 13px;
            margin-bottom: 5px;
        }

        .swot-item-meta {
            font-size: 11px;
            color: #6b7280;
            display: flex;
            justify-content: space-between;
        }

        .summary-box {
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border-right: 4px solid #10b981;
            margin-bottom: 20px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .summary-content {
            font-size: 14px;
            line-height: 1.8;
            color: #4b5563;
            white-space: pre-line;
        }

        .strategies-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .strategy-card {
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .strategy-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid;
        }

        .strategy-content {
            font-size: 13px;
            line-height: 1.6;
            white-space: pre-line;
        }

        .action-plan {
            margin-top: 30px;
        }

        .action-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .action-table th {
            background: #f3f4f6;
            padding: 10px;
            text-align: right;
            font-weight: 600;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .action-table td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
        }

        .priority-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Colors for SWOT sections */
        .strength {
            background-color: #10b981;
        }

        .weakness {
            background-color: #ef4444;
        }

        .opportunity {
            background-color: #3b82f6;
        }

        .threat {
            background-color: #f59e0b;
        }

        /* Colors for priority badges */
        .priority-high {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .priority-medium {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .priority-low {
            background-color: #f0fdf4;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        /* Print styles */
        @media print {
            body {
                padding: 10px;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>تقرير تحليل SWOT</h1>
        <div class="subtitle">{{ $project->title }}</div>
    </div>

    <!-- Metadata -->
    <div class="meta-info">
        <div class="meta-item">
            <div class="meta-label">تاريخ التقرير</div>
            <div class="meta-value">{{ $reportDate }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">المالك</div>
            <div class="meta-value">{{ $user->name }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">تاريخ الإنشاء</div>
            <div class="meta-value">{{ $project->created_at->format('Y/m/d') }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">الحالة</div>
            <div class="meta-value">
                @if ($project->is_finalized)
                    مكتمل
                @else
                    جاري العمل
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="section">
        <div class="section-title">الإحصائيات العامة</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">إجمالي العناصر</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['strength'] }}</div>
                <div class="stat-label">نقاط القوة</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['weakness'] }}</div>
                <div class="stat-label">نقاط الضعف</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['participants'] }}</div>
                <div class="stat-label">عدد المشاركين</div>
            </div>
        </div>
    </div>

    <!-- SWOT Analysis Items -->
    <div class="section">
        <div class="section-title">تفاصيل تحليل SWOT</div>
        <div class="swot-grid">
            @foreach ($categories as $type => $category)
                <div class="swot-section">
                    <div class="swot-header {{ $type }}" style="background-color: {{ $category['color'] }}">
                        {{ $category['title'] }} ({{ $stats[$type] }})
                    </div>
                    <div class="swot-content">
                        @forelse($project->boards->where('type', $type) as $item)
                            <div class="swot-item">
                                <div class="swot-item-text">{{ $item->content }}</div>
                                <div class="swot-item-meta">
                                    <span>{{ $item->participant_name }}</span>
                                    <span>{{ $item->created_at->format('Y/m/d') }}</span>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #9ca3af; padding: 20px;">
                                لا توجد عناصر
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Page break for finalization content -->
    <div class="page-break"></div>

    @if ($project->finalize)
        <!-- Finalization Summary -->
        <div class="section">
            <div class="section-title">ملخص وتحليل النتائج</div>
            <div class="summary-box">
                <div class="summary-title">ملخص شامل للتحليل</div>
                <div class="summary-content">
                    {{ $project->finalize->summary ?: 'لم يتم إضافة ملخص' }}
                </div>
            </div>
        </div>

        <!-- Strategies -->
        <div class="section">
            <div class="section-title">الاستراتيجيات المقترحة</div>
            <div class="strategies-grid">
                <div class="strategy-card">
                    <div class="strategy-title" style="color: #10b981; border-bottom-color: #10b981;">استراتيجية نقاط
                        القوة</div>
                    <div class="strategy-content">
                        {{ $project->finalize->strength_strategy ?: '-' }}
                    </div>
                </div>

                <div class="strategy-card">
                    <div class="strategy-title" style="color: #ef4444; border-bottom-color: #ef4444;">استراتيجية نقاط
                        الضعف</div>
                    <div class="strategy-content">
                        {{ $project->finalize->weakness_strategy ?: '-' }}
                    </div>
                </div>

                <div class="strategy-card">
                    <div class="strategy-title" style="color: #f59e0b; border-bottom-color: #f59e0b;">استراتيجية
                        التهديدات</div>
                    <div class="strategy-content">
                        {{ $project->finalize->threat_strategy ?: '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Plan -->
        @if ($project->finalize->action_items && count($project->finalize->action_items))
            <div class="section action-plan">
                <div class="section-title">خطة العمل التنفيذية</div>
                <table class="action-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">المهمة</th>
                            <th style="width: 20%;">المسؤول</th>
                            <th style="width: 20%;">الأولوية</th>
                            <th style="width: 20%;">موعد التسليم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->finalize->action_items as $item)
                            <tr>
                                <td>{{ $item['title'] }}</td>
                                <td>{{ $item['owner'] ?: '-' }}</td>
                                <td>
                                    @php
                                        $priorityClass = 'priority-medium';
                                        if (isset($item['priority'])) {
                                            $priorityClass = 'priority-' . strtolower($item['priority']);
                                        }
                                    @endphp
                                    <span class="priority-badge {{ $priorityClass }}">
                                        {{ $item['priority'] ?: 'غير محدد' }}
                                    </span>
                                </td>
                                <td>{{ $item['deadline'] ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>تم إنشاء هذا التقرير بواسطة نظام تحليل SWOT</div>
        <div style="margin-top: 5px;">التاريخ: {{ $reportDate }} | المعرف:
            SWOT-{{ $project->id }}-{{ time() }}</div>
    </div>
</body>

</html>
