<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8 space-y-8 py-4">
        <h1 class="text-3xl font-bold text-gray-800">
            تقارير: {{ $questionnaire->title }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach ($chartData as $questionId => $data)
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h3 class="font-bold text-gray-700 mb-4">{{ $data['question_text'] }}</h3>
                    <canvas id="chart-{{ $questionId }}"></canvas>
                </div>
            @endforeach
        </div>
    </div>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);

            for (const questionId in chartData) {
                const ctx = document.getElementById(`chart-${questionId}`).getContext('2d');
                const data = chartData[questionId];

                new Chart(ctx, {
                    type: data.type,
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'عدد الإجابات',
                            data: data.data,
                            backgroundColor: [ // Add more colors as needed
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                            ],
                            borderColor: 'rgba(255, 255, 255, 0.8)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
