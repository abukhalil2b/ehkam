<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricsDictionary extends Model
{
    use HasFactory;

    protected $table = 'metrics_dictionary';
    protected $guarded = [];
}
