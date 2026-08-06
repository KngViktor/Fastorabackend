<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets an admin pick which email marketing platform the footer's newsletter
 * form actually feeds, and hold its credentials — instead of that being a
 * developer-only decision baked into code. `newsletter_list_id` doubles as
 * "audience/list/group/form ID" depending on the provider; each one calls it
 * something different, but every provider needs exactly one such value.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('newsletter_provider')->nullable()->after('notification_email');
            $table->text('newsletter_api_key')->nullable()->after('newsletter_provider');
            $table->string('newsletter_list_id')->nullable()->after('newsletter_api_key');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['newsletter_provider', 'newsletter_api_key', 'newsletter_list_id']);
        });
    }
};
