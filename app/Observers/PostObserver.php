<?php

namespace App\Observers;

use App\Mail\NewPostMail;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Support\RevalidatesFrontend;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PostObserver
{
    public function saved(Post $post): void
    {
        $paths = ['/insights', '/insights/' . $post->slug];

        if ($post->wasChanged('slug') && $post->getOriginal('slug')) {
            $paths[] = '/insights/' . $post->getOriginal('slug');
        }

        RevalidatesFrontend::revalidate($paths);

        // Only the moment a post first goes live — not a brand new draft, and
        // not a later edit to a post that's already published, or every typo
        // fix would re-blast the whole list.
        if ($post->status === 'published' && ($post->wasRecentlyCreated || $post->wasChanged('status'))) {
            $this->broadcastToSubscribers($post);
        }
    }

    private function broadcastToSubscribers(Post $post): void
    {
        $frontendUrl = rtrim((string) config('services.frontend.url'), '/');

        if (! $frontendUrl) {
            return;
        }

        $url = "{$frontendUrl}/insights/{$post->slug}";

        NewsletterSubscriber::query()->pluck('email')->each(function (string $email) use ($post, $url) {
            try {
                Mail::to($email)->send(new NewPostMail($post, $url));
            } catch (Throwable $e) {
                // One bad or bouncing address must never stop the rest of the list.
                report($e);
            }
        });
    }

    public function deleted(Post $post): void
    {
        RevalidatesFrontend::revalidate(['/insights', '/insights/' . $post->slug]);
    }
}
