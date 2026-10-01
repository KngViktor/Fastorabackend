<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The "This article is part of Fastora's Journal…" line at the foot of an
 * article is now printed by the frontend, behind a per-post toggle (on by
 * default, off for anything that isn't part of the Journal), rather than typed
 * into each post's content by hand.
 *
 * Existing posts carry a hand-typed copy of that line as their own paragraph,
 * which would now print twice, so it is stripped from their content here.
 * Matched on the opening words, so a copy an editor italicised or reworded the
 * ending of is still caught; nothing else in a post is touched.
 *
 * down() drops the column but does not put the stripped paragraphs back: which
 * posts had one isn't recorded, and the frontend stops printing the line
 * either way.
 */
return new class extends Migration
{
    private const HAND_TYPED_NOTE = '#<p\b[^>]*>(?:(?!</p>).)*?This article is part of Fastora(?:(?!</p>).)*</p>\s*#is';

    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('show_journal_note')->default(true)->after('featured');
        });

        DB::table('posts')
            ->where('content', 'like', '%This article is part of Fastora%')
            ->get(['id', 'content'])
            ->each(function ($post) {
                $content = rtrim(preg_replace(self::HAND_TYPED_NOTE, '', $post->content));

                DB::table('posts')->where('id', $post->id)->update([
                    'content' => $content,
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('show_journal_note');
        });
    }
};
