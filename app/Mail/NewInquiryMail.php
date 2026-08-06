<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry) {}

    public function build(): self
    {
        $subject = $this->inquiry->kind === 'consultation'
            ? "Consultation request from {$this->inquiry->name}"
            : "New enquiry from {$this->inquiry->name}" . ($this->inquiry->company ? " ({$this->inquiry->company})" : '');

        return $this
            ->subject($subject)
            ->replyTo($this->inquiry->email, $this->inquiry->name)
            ->view('emails.new-inquiry', ['inquiry' => $this->inquiry, 'fields' => $this->fields()]);
    }

    /** Only the fields actually filled in — a blank "Phone: —" row helps no one. */
    private function fields(): array
    {
        $i = $this->inquiry;

        return array_filter([
            'Name' => $i->name,
            'Email' => $i->email,
            'Phone' => $i->phone,
            'Website' => $i->website_url,
            'Company' => $i->company,
            'Service' => $i->serviceNeeded?->title,
            'Budget' => $i->budget_range,
            'Timeline' => $i->timeline,
            'Times they can make' => $i->preferred_times,
            'Their timezone' => $i->timezone,
        ], fn ($value) => filled($value));
    }
}
