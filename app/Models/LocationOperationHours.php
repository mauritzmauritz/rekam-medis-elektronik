<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationOperationHours extends Model
{
    protected $table = 'location_operation_hours';

    protected $casts = [
        'days_of_week' => 'array',
        'all_day' => 'boolean',
        'opening_time' => 'time',
        'closing_time' => 'time',
    ];

    public $timestamps = false;

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
