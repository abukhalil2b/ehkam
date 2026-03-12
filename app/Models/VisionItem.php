<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisionItem extends Model
{
    protected $guarded = [];

    /**
     * جلب العنصر الأب (مثل جلب المحور التابع له هذه الأولوية)
     */
    public function parent()
    {
        return $this->belongsTo(VisionItem::class, 'parent_id');
    }

    /**
     * جلب العناصر الفرعية (مثل جلب الأولويات التابعة لهذا المحور)
     */
    public function children()
    {
        return $this->hasMany(VisionItem::class, 'parent_id');
    }

    /**
     * جلب المؤشرات المرتبطة بعنصر الرؤية هذا
     */
    public function indicators()
    {
        return $this->hasMany(Indicator::class, 'vision_item_id');
    }
}