<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Post $post, public string $url) {}

    public function build(): self
    {
        return $this
            ->subject($this->post->title)
            ->view('emails.new-post', [
                'post' => $this->post,
                'url' => $this->url,
                'excerpt' => $this->excerpt(),
            ]);
    }

    private function excerpt(): string
    {
        if (filled($this->post->meta_description)) {
            return $this->post->meta_description;
        }

        return Str::limit(trim(strip_tags((string) $this->post->content)), 200);
    }
}
