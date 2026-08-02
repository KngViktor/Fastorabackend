<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    /** All published pages (for the sitemap) — full PageResource shape, list form. */
    public function index()
    {
        return PageResource::collection(Page::published()->with(['heroMedia', 'metaImage'])->get());
    }

    public function show(string $slug)
    {
        $page = Page::published()
            ->where('slug', $slug)
            ->with(['heroMedia', 'metaImage'])
            ->firstOrFail();

        return new PageResource($page);
    }

    /**
     * All published page slugs — lets the frontend build static params.
     * Wrapped in a "data" key to match every other endpoint's envelope
     * (JsonResource::collection() does this automatically; a bare Eloquent
     * collection returned directly does not, so it's done explicitly here).
     */
    public function slugs()
    {
        return response()->json(['data' => Page::published()->pluck('slug')]);
    }
}
