<?php

namespace App\Observers;

use App\Models\CaseStudy;
use App\Support\RevalidatesFrontend;

class CaseStudyObserver
{
    public function saved(CaseStudy $caseStudy): void
    {
        $paths = ['/case-studies', '/case-studies/' . $caseStudy->slug];

        if ($caseStudy->wasChanged('slug') && $caseStudy->getOriginal('slug')) {
            $paths[] = '/case-studies/' . $caseStudy->getOriginal('slug');
        }

        RevalidatesFrontend::revalidate($paths);
    }

    public function deleted(CaseStudy $caseStudy): void
    {
        RevalidatesFrontend::revalidate(['/case-studies', '/case-studies/' . $caseStudy->slug]);
    }
}
