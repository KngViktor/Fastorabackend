<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'source',
        'synced_to_provider',
    ];

    protected $casts = [
        'synced_to_provider' => 'boolean',
    ];
}
