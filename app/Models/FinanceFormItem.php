<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceFormItem extends Model
{
   protected $guarded = [];

    public function need()
    {
        return $this->belongsTo(FinanceNeed::class, 'finance_need_id');
    }

    public function form()
    {
        return $this->belongsTo(FinanceForm::class, 'finance_form_id');
    }
}
