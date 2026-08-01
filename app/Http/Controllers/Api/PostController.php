<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()
            ->with(['heroImage', 'metaImage', 'categories', 'authors'])
            ->orderByDesc('published_at');

        if ($limit = $request->integer('limit')) {
            $query->limit($limit);
        }

        return PostResource::collection($query->get());
    }

    public function show(string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['heroImage', 'metaImage', 'categories', 'authors'])
            ->firstOrFail();

        return new PostResource($post);
    }
}
