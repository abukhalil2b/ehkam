<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;
use App\Models\Wilayat;

class GeographicSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'مسقط' => ['مسقط', 'مطرح', 'العامرات', 'بوشر', 'السيب', 'قريات'],
            'ظفار' => ['صلالة', 'طاقة', 'مرباط', 'رخيوت', 'ثمريت', 'ضلكوت', 'المزيونة', 'مقشن', 'شليم وجزر الحلانيات', 'سدح'],
            'مسندم' => ['خصب', 'دبا', 'بخا', 'مدحاء'],
            'البريمي' => ['البريمي', 'محضة', 'السنينة'],
            'الداخلية' => ['نزوى', 'بهلاء', 'منح', 'الحمراء', 'أدم', 'إزكي', 'سمائل', 'بدبد', 'الجبل الأخضر'],
            'شمال الباطنة' => ['صحار', 'شناص', 'لوى', 'صحم', 'الخابورة', 'السويق'],
            'جنوب الباطنة' => ['الرستاق', 'العوابي', 'نخل', 'وادي المعاول', 'بركاء', 'المصنعة'],
            'جنوب الشرقية' => ['صور', 'الكامل والوافي', 'جعلان بني بوحسن', 'جعلان بني بوعلي', 'مصيرة'],
            'شمال الشرقية' => ['إبراء', 'المضيبي', 'بدية', 'القابل', 'وادي بني خالد', 'دماء والطائيين', 'سناو'],
            'الظاهرة' => ['عبري', 'ينقل', 'ضنك'],
            'الوسطى' => ['هيماء', 'محوت', 'الدقم', 'الجازر'],
        ];

        foreach ($data as $govName => $wilayats) {
            // updateOrCreate prevents duplicate keys if the seeder is run multiple times
            $governorate = Governorate::updateOrCreate(['name' => $govName]);

            foreach ($wilayats as $wilayatName) {
                Wilayat::updateOrCreate([
                    'governorate_id' => $governorate->id,
                    'name' => $wilayatName
                ]);
            }
        }
    }
}
