<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceNeed extends Model
{
   protected $guarded = [];

    public function formItems()
    {
        return $this->hasMany(FinanceFormItem::class);
    }
}
