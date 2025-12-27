<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }} — شاشة العرض</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <style>
        body {
            background-color: #f9fafb;
        }
    </style>
</head>
<body>

<div class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow px-8 py-4">
        <h1 class="text-3xl font-bold text-gray-800 text-center">
            {{ $project->title }}
        </h1>
        <p class="text-center text-gray-600 mt-1">
            امسح رمز QR للمشاركة في التحليل
        </p>
    </header>

    <!-- Main Content -->
    <main class="flex-1 grid grid-cols-1 xl:grid-cols-3 gap-6 p-6">

        <!-- QR Code Section -->
        <section class="bg-white rounded-xl shadow flex flex-col items-center justify-center p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                رمز المشاركة
            </h2>

            <div class="mb-4">
                {!! $qrCode !!}
            </div>

            <p class="text-sm text-gray-500 text-center break-all">
                {{ $publicUrl }}
            </p>

            <p class="text-sm text-gray-600 mt-4 text-center">
                افتح الكاميرا وامسح الرمز ثم أدخل اسمك
            </p>
        </section>

        <!-- Live Board -->
        <section class="xl:col-span-2 bg-white rounded-xl shadow p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
                اللوحة المباشرة
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                @php
                    $sections = [
                        'strength' => ['title' => 'نقاط القوة', 'color' => 'green'],
                        'weakness' => ['title' => 'نقاط الضعف', 'color' => 'red'],
                        'opportunity' => ['title' => 'الفرص', 'color' => 'blue'],
                        'threat' => ['title' => 'التهديدات', 'color' => 'yellow'],
                    ];
                @endphp

                @foreach($sections as $type => $meta)
                    <div class="border-2 border-{{ $meta['color'] }}-200 rounded-lg p-4 bg-{{ $meta['color'] }}-50">
                        <h3 class="font-bold text-lg mb-3 text-{{ $meta['color'] }}-800 text-center">
                            {{ $meta['title'] }}
                        </h3>

                        <div class="space-y-2 max-h-[60vh] overflow-y-auto">
                            @forelse($project->boards->where('type', $type) as $item)
                                <div class="bg-white p-3 rounded shadow">
                                    <p class="text-sm text-gray-800">
                                        {{ $item->content }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        — {{ $item->participant_name }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 italic text-center">
                                    لا توجد عناصر بعد
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>
        </section>

    </main>

</div>

<script>
    // Auto refresh every 7 seconds for display screens
    setTimeout(() => location.reload(), 7000);
</script>

</body>
</html>
