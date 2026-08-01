<?php

namespace App\Observers;

use App\Models\Service;
use App\Support\RevalidatesFrontend;

class ServiceObserver
{
    public function saved(Service $service): void
    {
        $paths = ['/services', '/services/' . $service->slug];

        if ($service->wasChanged('slug') && $service->getOriginal('slug')) {
            $paths[] = '/services/' . $service->getOriginal('slug');
        }

        RevalidatesFrontend::revalidate($paths);
    }

    public function deleted(Service $service): void
    {
        RevalidatesFrontend::revalidate(['/services', '/services/' . $service->slug]);
    }
}
