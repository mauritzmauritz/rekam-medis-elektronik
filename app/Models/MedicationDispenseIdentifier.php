<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispenseIdentifier extends Model
{
    protected $table = 'medication_dispense_identifier';
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }
}
