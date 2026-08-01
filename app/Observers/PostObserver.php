<?php

namespace App\Observers;

use App\Models\Post;
use App\Support\RevalidatesFrontend;

class PostObserver
{
    public function saved(Post $post): void
    {
        $paths = ['/insights', '/insights/' . $post->slug];

        if ($post->wasChanged('slug') && $post->getOriginal('slug')) {
            $paths[] = '/insights/' . $post->getOriginal('slug');
        }

        RevalidatesFrontend::revalidate($paths);
    }

    public function deleted(Post $post): void
    {
        RevalidatesFrontend::revalidate(['/insights', '/insights/' . $post->slug]);
    }
}
