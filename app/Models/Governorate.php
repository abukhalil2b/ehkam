<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $guarded = [];

    // المحافظة تحتوي على عدة ولايات
    public function wilayats()
    {
        return $this->hasMany(Wilayat::class);
    }

    // إضافة العلاقات للإحصائيات (اختياري لجلب الإحصائيات لمحافظة معينة مباشرة)
    public function guidanceStatistics()
    {
        return $this->hasMany(GuidanceStatistic::class);
    }
}