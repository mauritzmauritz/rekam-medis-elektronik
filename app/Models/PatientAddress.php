<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAddress extends Model
{
    use HasFactory;

    protected $table = 'patient_address';

    public $timestamps = false;

    protected $casts = [
        'line' => 'array'
    ];

    protected $guarded = ['id'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
