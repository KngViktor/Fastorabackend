<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;

class SiteSettingsController extends Controller
{
    public function show()
    {
        $settings = SiteSetting::current()->load(['logoLight', 'logoDark', 'favicon']);

        return new SiteSettingResource($settings);
    }
}
