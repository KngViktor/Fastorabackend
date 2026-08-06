<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $subscriber) {
            $subscriber->unsubscribe_token ??= Str::random(40);
        });
    }
}
