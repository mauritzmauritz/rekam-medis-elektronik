<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationIngredient extends Model
{
    protected $table = 'medication_ingredient';
    protected $casts = [
        'is_active' => 'boolean',
        'strength_numerator_value' => 'decimal:2',
        'strength_denominator_value' => 'decimal:2'
    ];
    public $timestamps = false;

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
