<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Consolidates the admin login onto workwith@fastora.africa.
 *
 * The seeder originally created the super admin as hello@fastora.africa, while
 * the public contact address is workwith@fastora.africa. Changing the seeder
 * alone would not help the live site: `app:deploy` skips seeding once content
 * exists, and even a forced seed would create a *second* user rather than
 * rename the first, because the email is the updateOrCreate key.
 *
 * A migration is the right vehicle because `app:deploy` always runs migrations.
 *
 * Deliberately does not touch the password. Renaming the row preserves the
 * existing credential, so nobody has to set or transmit a new one, and any
 * account made separately with `make:filament-user` is left completely alone.
 */
return new class extends Migration
{
    private const OLD_EMAIL = 'hello@fastora.africa';

    private const NEW_EMAIL = 'workwith@fastora.africa';

    public function up(): void
    {
        $existing = DB::table('users')->where('email', self::NEW_EMAIL)->first();

        // Already present, perhaps created by hand. Just make sure it can
        // actually administer the panel, which is the point of the change.
        if ($existing !== null) {
            if ($existing->role !== 'super_admin') {
                DB::table('users')
                    ->where('email', self::NEW_EMAIL)
                    ->update(['role' => 'super_admin', 'updated_at' => now()]);
            }

            return;
        }

        // Rename the seeded admin, keeping its password and its ownership of
        // any related records. Guarded so a database that never had the seeded
        // account is simply left untouched.
        DB::table('users')
            ->where('email', self::OLD_EMAIL)
            ->update([
                'email' => self::NEW_EMAIL,
                'role' => 'super_admin',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Only reverse the rename if it would not collide with a pre-existing
        // hello@ row.
        if (DB::table('users')->where('email', self::OLD_EMAIL)->exists()) {
            return;
        }

        DB::table('users')
            ->where('email', self::NEW_EMAIL)
            ->update(['email' => self::OLD_EMAIL, 'updated_at' => now()]);
    }
};
