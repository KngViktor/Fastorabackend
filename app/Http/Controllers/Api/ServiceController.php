<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::published()
            ->with(['icon', 'featuredImage', 'metaImage'])
            ->orderBy('order');

        if ($request->boolean('featuredOnHome')) {
            $query->where('featured_on_home', true);
        }

        if ($limit = $request->integer('limit')) {
            $query->limit($limit);
        }

        return ServiceResource::collection($query->get());
    }

    public function show(string $slug)
    {
        $service = Service::published()
            ->where('slug', $slug)
            ->with(['icon', 'featuredImage', 'metaImage'])
            ->firstOrFail();

        return new ServiceResource($service);
    }
}
