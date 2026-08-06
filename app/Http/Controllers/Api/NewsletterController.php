<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request, NewsletterService $newsletter)
    {
        // Honeypot — real users never fill this hidden field.
        if (filled($request->input('website'))) {
            return response()->json(['success' => true]);
        }

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:254'],
            'source' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = $validator->validated();

        // firstOrCreate rather than create: resubmitting an email that's
        // already on the list should feel like success, not a duplicate error.
        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            ['source' => $data['source'] ?? 'footer'],
        );

        if (! $subscriber->synced_to_provider && $newsletter->subscribe($subscriber->email)) {
            $subscriber->update(['synced_to_provider' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * The one-click link every broadcast email carries. Renders directly —
     * not a JSON endpoint — since a mail client opens this in a plain
     * browser tab with no frontend app behind it to hand the response to.
     *
     * An unrecognised or already-used token still shows success rather than
     * an error: from the visitor's side "I'm not on the list" and "I was
     * just removed from the list" are the same outcome.
     */
    public function unsubscribe(string $token)
    {
        NewsletterSubscriber::where('unsubscribe_token', $token)->delete();

        return view('newsletter.unsubscribed');
    }
}
