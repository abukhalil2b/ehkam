<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodTemplates = [
            'annually' => [
                ['name' => 'السنة'],
            ],
            'half_yearly' => [
                ['name' => 'النصف الأول'],
                ['name' => 'النصف الثاني'],
            ],
            'quarterly' => [
                ['name' => 'الربع الأول'],
                ['name' => 'الربع الثاني'],
                ['name' => 'الربع الثالث'],
                ['name' => 'الربع الرابع'],
            ],
            'monthly' => array_map(function ($i) {
                return ['name' => "شهر {$i}"];
            }, range(1, 12)),
        ];

        foreach ($periodTemplates as $cate => $templates) {
            foreach ($templates as $template) {
                DB::table('period_templates')->insert([
                    'cate' => $cate,
                    'name' => $template['name'],
                ]);
            }
        }
    }
}
