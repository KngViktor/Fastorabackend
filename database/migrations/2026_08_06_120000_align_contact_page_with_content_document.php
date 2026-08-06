<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Aligns the Contact page's header and FAQ with the client's content
 * document — the FAQ grows from 3 questions to 6, replacing rather than
 * appending, since the document's set supersedes the old one rather than
 * extending it.
 *
 * Guarded on the exact old heading, so an edited header/FAQ isn't overwritten.
 */
return new class extends Migration
{
    private const OLD_HEADING = "Let's start your project";

    private const NEW_HEADING = "Let's talk about your business.";

    private const NEW_DESCRIPTION = "Every project starts with a conversation. Tell us what you're working on, where you'd like to go, or the challenge you're trying to solve. We'll take it from there.";

    private const OLD_DESCRIPTION = "Tell us where you want to go. We'll come back with how to get there, fast.";

    private const OLD_FAQS = [
        ['question' => 'What happens after I submit the form?', 'answer' => "We'll review your message and follow up within one to two business days to schedule a consultation."],
        ['question' => 'Is the first consultation free?', 'answer' => 'Yes. The first consultation is a conversation about your business and communication goals, with no obligation.'],
        ['question' => 'What information should I include in my message?', 'answer' => "A short description of your business, what you're hoping to achieve, and which service you're interested in helps us prepare for the call."],
    ];

    private const NEW_FAQS = [
        ['question' => 'Do I need to know which service I need?', 'answer' => "Not at all. Many clients come to us with a challenge rather than a clear solution. We'll help you decide what makes the most sense after we've learned more about your business."],
        ['question' => 'What information should I include in my message?', 'answer' => "Tell us a little about your business, what you're hoping to achieve, and any challenges you're facing. If you already know which service you're interested in, you can include that too."],
        ['question' => 'Is the first conversation free?', 'answer' => "Yes. The first conversation is an opportunity for us to understand your business, answer your questions, and decide whether we're the right fit to work together."],
        ['question' => 'What happens after I get in touch?', 'answer' => "We'll review your message and, if it looks like we're a good fit, we'll get in touch to arrange a conversation and discuss the best next step for your business."],
        ['question' => 'How soon will I hear back?', 'answer' => 'We aim to respond to every enquiry within one business day.'],
        ['question' => 'Do you work with businesses outside Nigeria?', 'answer' => 'Yes. We work with businesses, founders, and organisations across Africa and in other parts of the world.'],
    ];

    public function up(): void
    {
        DB::table('pages')
            ->where('slug', 'contact')
            ->where('page_header_heading', self::OLD_HEADING)
            ->update([
                'page_header_heading' => self::NEW_HEADING,
                'page_header_description' => self::NEW_DESCRIPTION,
                'faqs' => json_encode(self::NEW_FAQS),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('slug', 'contact')
            ->where('page_header_heading', self::NEW_HEADING)
            ->update([
                'page_header_heading' => self::OLD_HEADING,
                'page_header_description' => self::OLD_DESCRIPTION,
                'faqs' => json_encode(self::OLD_FAQS),
                'updated_at' => now(),
            ]);
    }
};
