<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterDiagnosis extends FhirModel
{
    protected $table = 'encounter_diagnosis';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::DiagnosisRole
        ]
    ];
}
