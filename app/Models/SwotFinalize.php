<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwotFinalize extends Model
{
   protected $guarded = [];
   protected $casts = [
      'action_items' => 'array',
   ];
}
