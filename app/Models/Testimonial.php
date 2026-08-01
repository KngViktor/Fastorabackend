<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Testimonial extends Model
{
    protected $fillable = [
        'quote',
        'client_name',
        'role',
        'company',
        'avatar_media_id',
        'rating',
        'show_on_home',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'rating' => 'integer',
    ];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'avatar_media_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
}
