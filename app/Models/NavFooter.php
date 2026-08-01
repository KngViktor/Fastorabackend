<?php

namespace App\Models;

use App\Models\Concerns\SingletonModel;
use Illuminate\Database\Eloquent\Model;

class NavFooter extends Model
{
    use SingletonModel;

    protected $table = 'nav_footers';

    protected $fillable = ['nav_items'];

    protected $casts = [
        'nav_items' => 'array',
    ];
}
