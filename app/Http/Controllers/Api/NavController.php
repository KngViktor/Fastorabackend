<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NavResource;
use App\Models\NavFooter;
use App\Models\NavHeader;

class NavController extends Controller
{
    public function header()
    {
        return new NavResource(NavHeader::current());
    }

    public function footer()
    {
        return new NavResource(NavFooter::current());
    }
}
