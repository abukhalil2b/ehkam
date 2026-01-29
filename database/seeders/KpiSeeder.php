<?php

namespace Database\Seeders;

use App\Models\KpiIndicator;
use App\Models\KpiValue;
use Illuminate\Database\Seeder;

class KpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تعريف المؤشرات مع بياناتها
        $indicators = [
            [
                'code' => 'KPI-001',
                'title' => 'إجمالي العائد من الأصول الوقفية وبيت المال والأيتام',
                'unit' => 'currency',
                'currency' => 'ر.ع.',
                'category' => 'العوائد المالية',
                'description' => 'مجموع العوائد من الأوقاف وبيت المال والأيتام',
                'display_order' => 1,
                // القيمة السنوية المستهدفة: 9,261,000
                'yearly_target' => 9261000,
            ],
            [
                'code' => 'KPI-002',
                'title' => 'عائد الأوقاف',
                'unit' => 'currency',
                'currency' => 'ر.ع.',
                'category' => 'العوائد المالية',
                'description' => 'العائد السنوي من الأوقاف',
                'display_order' => 2,
                // القيمة السنوية المستهدفة: 6,284,250
                'yearly_target' => 6284250,
            ],
            [
                'code' => 'KPI-003',
                'title' => 'عائد بيت المال',
                'unit' => 'currency',
                'currency' => 'ر.ع.',
                'category' => 'العوائد المالية',
                'description' => 'العائد السنوي من بيت المال',
                'display_order' => 3,
                // القيمة السنوية المستهدفة: 661,500
                'yearly_target' => 661500,
            ],
            [
                'code' => 'KPI-004',
                'title' => 'عائد الأيتام والقصر',
                'unit' => 'currency',
                'currency' => 'ر.ع.',
                'category' => 'العوائد المالية',
                'description' => 'العائد السنوي من أموال الأيتام والقصر',
                'display_order' => 4,
                // القيمة السنوية المستهدفة: 2,315,250
                'yearly_target' => 2315250,
            ],
            [
                'code' => 'KPI-005',
                'title' => 'قيمة الأصول الوقفية الجديدة',
                'unit' => 'currency',
                'currency' => 'ر.ع.',
                'category' => 'نمو الأصول',
                'description' => 'قيمة الأصول الوقفية المضافة سنوياً',
                'display_order' => 5,
                // القيمة السنوية المستهدفة: 1,113,000
                'yearly_target' => 1113000,
            ],
            [
                'code' => 'KPI-006',
                'title' => 'إيرادات الزكاة',
                'unit' => 'currency',
                'currency' => 'ر.ع.',
                'category' => 'الزكاة',
                'description' => 'إجمالي إيرادات الزكاة السنوية',
                'display_order' => 6,
                // القيمة السنوية المستهدفة: 8,364,000
                'yearly_target' => 8364000,
            ],
            [
                'code' => 'KPI-007',
                'title' => 'المستفيدين من الأنشطة الدينية وخدمات الإفتاء',
                'unit' => 'number',
                'currency' => null,
                'category' => 'الخدمات الدينية',
                'description' => 'عدد المستفيدين من الأنشطة الدينية وخدمات الإفتاء',
                'display_order' => 7,
                // القيمة السنوية المستهدفة: 1,984,500
                'yearly_target' => 1984500,
            ],
            [
                'code' => 'KPI-008',
                'title' => 'متعلمي القرآن الكريم',
                'unit' => 'number',
                'currency' => null,
                'category' => 'القرآن الكريم',
                'description' => 'عدد متعلمي القرآن الكريم',
                'display_order' => 8,
                // القيمة السنوية المستهدفة: 19,211
                'yearly_target' => 19211,
            ],
            [
                'code' => 'KPI-009',
                'title' => 'برامج التسامح والتعايش (دولياً)',
                'unit' => 'number',
                'currency' => null,
                'category' => 'التسامح والتعايش',
                'description' => 'عدد المستفيدين من برامج التسامح والتعايش الدولية',
                'display_order' => 9,
                // القيمة السنوية المستهدفة: 11,965
                'yearly_target' => 11965,
            ],
            [
                'code' => 'KPI-010',
                'title' => 'برامج تعزيز الهوية الوطنية (محلياً)',
                'unit' => 'number',
                'currency' => null,
                'category' => 'الهوية الوطنية',
                'description' => 'عدد المستفيدين من برامج تعزيز الهوية الوطنية المحلية',
                'display_order' => 10,
                // القيمة السنوية المستهدفة: 11,025
                'yearly_target' => 11025,
            ],
        ];

        // نسب توزيع الأرباع (مع بعض التباين الواقعي)
        $quarterDistributions = [
            // year => [q1%, q2%, q3%, q4%]
            2023 => [0.22, 0.26, 0.24, 0.28],
            2024 => [0.23, 0.25, 0.27, 0.25],
            2025 => [0.25, 0.25, 0.25, 0.25], // متساوي للسنة الجارية
        ];

        // نسب الإنجاز المحققة (واقعية)
        $achievementRates = [
            // year => [q1, q2, q3, q4]
            2023 => [0.85, 0.92, 0.88, 0.95],
            2024 => [0.90, 0.95, 0.93, 0.97],
            2025 => [0.88, 0.00, 0.00, 0.00], // 2025 فقط الربع الأول منجز
        ];

        foreach ($indicators as $indicatorData) {
            $yearlyTarget = $indicatorData['yearly_target'];
            unset($indicatorData['yearly_target']);

            // إنشاء المؤشر
            $indicator = KpiIndicator::create($indicatorData);

            // إنشاء القيم للسنوات 2023، 2024، 2025
            foreach ([2023, 2024, 2025] as $year) {
                $distribution = $quarterDistributions[$year];
                $achievements = $achievementRates[$year];

                // زيادة المستهدف بنسبة 5% سنوياً
                $yearMultiplier = 1 + (($year - 2023) * 0.05);
                $adjustedYearlyTarget = $yearlyTarget * $yearMultiplier;

                for ($quarter = 1; $quarter <= 4; $quarter++) {
                    $quarterTarget = $adjustedYearlyTarget * $distribution[$quarter - 1];
                    $quarterActual = $quarterTarget * $achievements[$quarter - 1];

                    KpiValue::create([
                        'kpi_indicator_id' => $indicator->id,
                        'year' => $year,
                        'quarter' => $quarter,
                        'target_value' => round($quarterTarget, 2),
                        'actual_value' => round($quarterActual, 2),
                        'justification' => null,
                        'notes' => null,
                    ]);
                }
            }
        }

        $this->command->info('تم إنشاء ' . count($indicators) . ' مؤشر مع بيانات 3 سنوات (2023-2025)');
    }
}
