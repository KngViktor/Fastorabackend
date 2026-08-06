<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'status',
        // 'general' or 'consultation' — both forms share this inbox.
        'kind',
        'name',
        'email',
        'phone',
        'website_url',
        'company',
        'service_needed_id',
        'budget_range',
        'timeline',
        'brief',
        'preferred_times',
        'timezone',
    ];

    public function serviceNeeded(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_needed_id');
    }
}
