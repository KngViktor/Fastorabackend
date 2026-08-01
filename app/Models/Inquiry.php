<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'status',
        'name',
        'email',
        'company',
        'service_needed_id',
        'budget_range',
        'timeline',
        'brief',
    ];

    public function serviceNeeded(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_needed_id');
    }
}
