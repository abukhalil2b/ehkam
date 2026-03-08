<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayat extends Model
{
    protected $guarded = [];

    // الولاية تنتمي لمحافظة واحدة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}