<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepEvidenceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
