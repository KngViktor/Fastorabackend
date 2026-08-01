<?php

namespace App\Models;

use App\Models\Concerns\SingletonModel;
use Illuminate\Database\Eloquent\Model;

class NavHeader extends Model
{
    use SingletonModel;

    protected $table = 'nav_headers';

    protected $fillable = ['nav_items'];

    protected $casts = [
        'nav_items' => 'array',
    ];
}
