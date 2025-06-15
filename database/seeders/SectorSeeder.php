<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectorNames = [
            'ديوان عام الوزارة',
            'إدارة الأوقاف والشؤون الدينة بمحافظة جنوب الباطنة',
            'إدارة الأوقاف والشؤون الدينة بمحافظة شمال الباطنة',
            'إدارة الأوقاف والشؤون الدينة بمحافظة الداخلية',
            'إدارة الأوقاف والشؤون الدينة بمحافظة الظاهرة',
            'إدارة الأوقاف والشؤون الدينة الوسطى',
            'إدارة الأوقاف والشؤون بمحافظة ظفار',
            'لجنة الزكاة بولاية السيب',
            'لجنة الزكاة بولاية العوابي',
            'المؤسسة الوقفية بولاية بوشر',
            'مؤسسة جابر بن زيد الوقفية',
        ];

        foreach ($sectorNames as $name) {
            DB::table('sectors')->insert([
                'name' => $name,
            ]);
        }
    }
}
