<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets an admin point outgoing mail (contact/consultation notifications,
 * password resets) at their own SMTP account from the dashboard, instead of
 * a developer editing MAIL_* in .env. `mail_password` is stored through the
 * model's `encrypted` cast, not plain text.
 *
 * All nullable: an empty mail_host means "keep using whatever config/mail.php
 * already has" (see MailSettings::apply()), so installs that never touch
 * this tab keep working exactly as before.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('mail_host')->nullable()->after('newsletter_subheading');
            $table->unsignedInteger('mail_port')->nullable()->after('mail_host');
            $table->string('mail_username')->nullable()->after('mail_port');
            $table->text('mail_password')->nullable()->after('mail_username');
            $table->string('mail_encryption')->nullable()->after('mail_password');
            $table->string('mail_from_address')->nullable()->after('mail_encryption');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
            $table->string('notification_email')->nullable()->after('mail_from_name');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
                'notification_email',
            ]);
        });
    }
};
