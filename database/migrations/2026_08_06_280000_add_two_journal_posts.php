<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Two Journal posts supplied as finished copy plus their own cover images.
 * Self-contained rather than assuming app:sync-media has already copied
 * the cover images — migrations run before the media sync in app:deploy,
 * so a migration that only looked up a Media row by filename would find
 * nothing on the very deploy that introduces it.
 */
return new class extends Migration
{
    public function up(): void
    {
        $posts = require database_path('data/reference-posts-2026-08-06.php');
        $authorId = DB::table('users')->where('email', 'hello@fastora.africa')->value('id');

        foreach ($posts as $post) {
            if (DB::table('posts')->where('slug', $post['slug'])->exists()) {
                continue;
            }

            $mediaId = $this->registerImage($post['image_filename'], $post['image_alt']);
            $categoryId = DB::table('categories')->where('slug', $post['category_slug'])->value('id');

            $postId = DB::table('posts')->insertGetId([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'hero_image_media_id' => $mediaId,
                'content' => $post['content'],
                'tags' => json_encode($post['tags']),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => $post['meta_title'],
                'meta_description' => $post['meta_description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($categoryId) {
                DB::table('category_post')->insert(['category_id' => $categoryId, 'post_id' => $postId]);
            }

            if ($authorId) {
                DB::table('post_user')->insert(['user_id' => $authorId, 'post_id' => $postId]);
            }
        }
    }

    private function registerImage(string $filename, string $alt): ?int
    {
        $source = database_path('seeders/images/' . $filename);

        if (! is_file($source)) {
            return null;
        }

        $path = 'seed/' . $filename;
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $disk->put($path, file_get_contents($source));
        }

        $existing = DB::table('media')->where('path', $path)->where('disk', 'public')->first();

        if ($existing) {
            return $existing->id;
        }

        $dimensions = @getimagesize($disk->path($path));

        return DB::table('media')->insertGetId([
            'disk' => 'public',
            'path' => $path,
            'filename' => $filename,
            'mime_type' => 'image/png',
            'size' => $disk->size($path),
            'alt' => $alt,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $posts = require database_path('data/reference-posts-2026-08-06.php');

        DB::table('posts')->whereIn('slug', array_column($posts, 'slug'))->delete();
    }
};
