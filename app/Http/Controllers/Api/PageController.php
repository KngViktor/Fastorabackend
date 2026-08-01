<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::published()
            ->where('slug', $slug)
            ->with(['heroMedia', 'metaImage'])
            ->firstOrFail();

        return new PageResource($page);
    }

    /** All published page slugs — lets the frontend build static params. */
    public function slugs()
    {
        return Page::published()->pluck('slug');
    }
}
