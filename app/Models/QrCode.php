<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = ['title', 'content', 'written_by'];

    public function author()
    {
        return $this->belongsTo(User::class, 'written_by');
    }
}
