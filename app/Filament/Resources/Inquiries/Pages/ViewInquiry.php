<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * Default screen for an enquiry: read it, then reply.
 *
 * Edit is still reachable from the header, for the status field and the odd typo
 * in a phone number — but reading no longer starts by opening an editable form
 * over a record of what somebody actually sent.
 */
class ViewInquiry extends ViewRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
