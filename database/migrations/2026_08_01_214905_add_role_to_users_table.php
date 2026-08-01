<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Four-tier role hierarchy:
            //   super_admin — unrestricted; the only role that can manage other admins/super_admins.
            //   admin       — full content management + manages editor/commenter users.
            //   editor      — full content management; no access to Users or Site/Nav Settings.
            //   commenter   — read-only everywhere.
            $table->enum('role', ['super_admin', 'admin', 'editor', 'commenter'])
                ->default('editor')
                ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
