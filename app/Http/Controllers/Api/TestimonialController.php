<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query()->with('avatar');

        if ($request->boolean('showOnHome')) {
            $query->where('show_on_home', true);
        }

        if ($serviceId = $request->integer('relatedService')) {
            $query->whereHas('services', fn ($q) => $q->where('services.id', $serviceId));
        }

        if ($limit = $request->integer('limit')) {
            $query->limit($limit);
        }

        return TestimonialResource::collection($query->get());
    }
}
