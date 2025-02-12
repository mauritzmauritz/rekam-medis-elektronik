<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterClassHistory extends FhirModel
{
    protected $table = 'encounter_class_history';
    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public const ENC_CLASS = [
        'binding' => [
            'valueset' => Valuesets::EncounterClass
        ]
    ];
}
