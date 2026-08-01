<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseStudyResource;
use App\Models\CaseStudy;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = CaseStudy::published()
            ->with(['coverImage', 'metaImage', 'relatedService'])
            ->orderBy('order');

        if ($request->boolean('featuredOnHome')) {
            $query->where('featured_on_home', true);
        }

        if ($limit = $request->integer('limit')) {
            $query->limit($limit);
        }

        return CaseStudyResource::collection($query->get());
    }

    public function show(string $slug)
    {
        $caseStudy = CaseStudy::published()
            ->where('slug', $slug)
            ->with(['coverImage', 'metaImage', 'relatedService'])
            ->firstOrFail();

        return new CaseStudyResource($caseStudy);
    }
}
