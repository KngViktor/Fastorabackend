<?php

/**
 * The confirmed client list, shared by the seeder and the migration that adds it
 * to an existing database.
 *
 * These are real clients, unlike the demo case studies and testimonials.
 * Industries are confirmed; project detail is not, which is why they appear as a
 * client wall rather than as case studies.
 *
 * No logo files yet. The Trusted By block renders a client as its name until a
 * logo is attached in the admin, so this can go live now and each entry can gain
 * its mark later without another migration.
 *
 * @return array<int, array{name: string, industry: string, media: null}>
 */
return [
    ['name' => 'Biografrica', 'industry' => 'Media', 'media' => null],
    ['name' => 'The Perfumes Room', 'industry' => 'E-commerce', 'media' => null],
    ['name' => 'Energia', 'industry' => 'Oil & Gas', 'media' => null],
    ['name' => 'Dynamite Agency', 'industry' => 'Marketing & Advertising', 'media' => null],
    ['name' => 'Unity Key Group', 'industry' => 'Real Estate', 'media' => null],
    ['name' => 'Infuzed', 'industry' => 'Food & Drinks', 'media' => null],
];
