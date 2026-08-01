<?php

namespace App\Observers;

use App\Models\Page;
use App\Support\RevalidatesFrontend;

class PageObserver
{
    protected function pathForSlug(?string $slug): ?string
    {
        if (! $slug) {
            return null;
        }

        return $slug === 'home' ? '/' : '/' . $slug;
    }

    public function saved(Page $page): void
    {
        $paths = array_filter([$this->pathForSlug($page->slug)]);

        if ($page->wasChanged('slug')) {
            $paths[] = $this->pathForSlug($page->getOriginal('slug'));
        }

        RevalidatesFrontend::revalidate(array_values(array_filter($paths)));
    }

    public function deleted(Page $page): void
    {
        $path = $this->pathForSlug($page->slug);
        if ($path) {
            RevalidatesFrontend::revalidate([$path]);
        }
    }
}
