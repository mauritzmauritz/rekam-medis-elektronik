<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudyProcedure extends Model
{
    protected $table = 'imaging_study_procedure';
    public $timestamps = false;

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_id');
    }
}
