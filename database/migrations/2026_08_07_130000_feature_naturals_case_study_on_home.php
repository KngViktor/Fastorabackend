<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Swaps which case study is featured on the home page: Energia Limited comes
 * off, Naturals by Jelique goes on. Matched by slug rather than replayed from
 * reference-case-studies.php wholesale, since that file also carries the four
 * studies' full copy and this change is scoped to one boolean each.
 *
 * A fresh database never sees this: the seeder and the case-study-install
 * migration both read reference-case-studies.php directly, which already
 * reflects this pairing.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('case_studies')
            ->where('slug', 'energia-corporate-communications')
            ->update(['featured_on_home' => false, 'updated_at' => now()]);

        DB::table('case_studies')
            ->where('slug', 'naturals-by-jelique-founder-story')
            ->update(['featured_on_home' => true, 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('case_studies')
            ->where('slug', 'energia-corporate-communications')
            ->update(['featured_on_home' => true, 'updated_at' => now()]);

        DB::table('case_studies')
            ->where('slug', 'naturals-by-jelique-founder-story')
            ->update(['featured_on_home' => false, 'updated_at' => now()]);
    }
};
