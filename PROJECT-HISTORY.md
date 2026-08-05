# Fastora: how the site got to where it is

Written for whoever picks this up next, including us in six months. It explains
the decisions that are not obvious from reading the code, and the host quirks
that explain why several things are done the odd way.

If you only read one section, read [The host shaped everything](#the-host-shaped-everything).

## What exists

One website, two repositories.

| | Repo | Runs at |
|---|---|---|
| Frontend | `KngViktor/fastora` | `fastora.africa` |
| Backend, CMS, API | `KngViktor/Fastorabackend` | `api.fastora.africa`, admin at `/admin` |

The frontend is Next.js 16 and holds no database. The backend is Laravel 12 with
Filament v4 on MySQL, and is the system of record for every word and image on the
site. Content flows one way: editors work in the Filament admin, which serves JSON
from `api.fastora.africa/api`, which the frontend renders.

## The short version of the history

The site was first built as a single Next.js app with **Payload CMS 3** embedded
in it, on **Supabase Postgres**, deployed to **Vercel**. That worked, but the
hosting plan the client actually had was shared hosting with MySQL and no Node
tooling at deploy time — which Payload could not run on.

So the CMS was rebuilt as a separate Laravel + Filament application against
MySQL, the frontend was rewired to consume it over HTTP, and the Payload stack was
eventually deleted. The frontend kept its components; only its data layer changed.

That migration is the single biggest fact about this codebase, and most of the
odd-looking decisions below descend from it.

## Timeline

Roughly, by phase rather than by date.

**1. Original build (Payload era).** Scaffold, dark agency theme, page routes,
contact form, post tags, user roles, an SEO/AEO layer (sitemap, robots,
`llms.txt`, JSON-LD), a custom-branded Payload admin with its own dashboard and
login, and a long run of hero-design iterations. Geo-based currency switching was
built as a foundation and then hidden from the header; it is still in the code but
not surfaced.

**2. Laravel backend built alongside.** Schema, Filament resources for every
collection, role-based policies, a branded split-screen login, password reset over
Resend, the read-only REST API, and a revalidation webhook so content changes
could reach the frontend.

**3. The rewire.** `e39e611` on the frontend points every fetch at the Laravel API
instead of Payload's local API. Everything after this is a two-app system.

**4. Deployment, which took several attempts.** See below — this is where most of
the hard-won knowledge is.

**5. Payload removal.** `9ce93e2` deletes the whole Payload tree: 118 files, about
31,700 lines, and 10 of 24 dependencies. `npm audit` went from 23 findings to 4.
Until then the app was still building `/admin`, `/api/graphql` and `/login` routes
pointed at a Postgres database that no longer existed.

**6. Content restructure from the client's copy document.** Ten services became
four, with the old names becoming what each one covers. Real client testimonials
replaced demo ones. A consultation page and per-service request forms were added.

**7. A security pass** against a review checklist, which found a functional bug as
well as the hardening items.

## The host shaped everything

Hostinger shared hosting disallows more than you would expect, and each
restriction is why some piece of this repo looks unusual. Please do not "clean
these up" without checking.

**Composer cannot run.** `proc_open` is disabled on the deploy runner, so
`composer install` fails on every push. **`vendor/` is therefore committed** —
built with `--no-dev`. After changing `composer.json` you must run
`composer install --no-dev --optimize-autoloader` locally and commit the result.
This is also why `predis` is absent and `CACHE_STORE=redis` is not yet an option.

**npm cannot run either**, so `public/build` (the compiled Filament theme) is
committed for the same reason. After editing
`resources/css/filament/admin/theme.css`, run `npm run build` and commit the
output.

**The document root is locked to `public_html`**, which is the repo root rather
than `public/`. A root `.htaccess` rewrites requests into `public/`. Without it the
site 403s.

**Symlinks are unavailable, and so is `exec`.** `php artisan storage:link` cannot
work: Laravel tries `symlink()`, falls back to `exec('ln -s ...')`, and both are
disabled — the fallback threw `Call to undefined function
Illuminate\Filesystem\exec()` and killed the whole deploy command. Instead the
root `.htaccess` maps `/storage` onto `storage/app/public`. Two details in that
rule cost real time:

- It must use `[L]`, not `[END]`. This host ignores `[END]`, which silently skipped
  the whole rule and every image 404'd.
- It needs a `(?!app/public/)` guard, or the rewrite matches its own output and
  loops into a 500.

**Deploys copy files but run no commands.** This is the one that keeps biting.
Pulling the repo updates PHP instantly, so code changes appear to work — but
migrations, seeding and media sync never happen on their own. **Nothing involving
the database is live until you run `php artisan app:deploy`.** If content looks
stale after a deploy, this is almost always why.

## Deploying

In the backend directory on the server — the folder containing `artisan`, normally
`~/domains/api.fastora.africa/public_html`:

```bash
php artisan app:deploy
```

That runs migrations, syncs bundled media, clears the API cache, rebuilds the
config/route/view caches, asks the frontend to revalidate, and finally attempts the
storage link (skipped safely on this host).

It is safe to re-run. Seeding only happens when the database has no pages, so it
cannot overwrite live content; `--force-seed` overrides that and will.

Worth setting up as a Hostinger cron job so it runs after every deploy. Frontend
deploys are ordinary Node builds and need nothing special, except that
`npm run start:server` (which runs `server.js`) is the start command for
Passenger-style hosts.

## How content works

Every visible string is meant to be editable in the admin. Where copy is still
hardcoded in the frontend, that is a gap, not a design choice.

**Two paths must agree.** Content is applied in two ways: the seeder, for a fresh
database, and a migration, for the live one. `app:deploy` **migrates before it
seeds**, which creates a trap that caught us more than once: on an empty database a
backfill migration finds nothing to update, so the seeder's own copy is what
survives — and a fresh install would come up with placeholder text while the live
site had the real thing.

The fix is `database/data/*.php`: plain files returning arrays, read by **both** the
seeder and the migration, so the two cannot drift. If you add content, put it
there.

```
database/data/
  services.php              the four services, full page copy
  reference-services.php    per-service copy shared with the backfill
  reference-about-page.php  the About page
  consultation-page.php     the consultation page
  confirmed-clients.php     the client wall
  social-links.php          official social accounts
  testimonials.php          real client reviews, with notes on what was excluded
```

**Media does not travel through git.** `storage/app/public` carries a `*`
.gitignore, so bundled photography cannot arrive by pull. `app:sync-media` copies
`database/seeders/images/*` onto the public disk on every deploy. It skips files
that already exist, so a photo replaced in the admin is never clobbered. This is
why every image on the site once 404'd: the photography was added to the seeder
*after* the first deploy had already populated the database, so the seeder never
ran again.

## Caching, and why content used to look stale

Three layers, each of which caused a "the site is not updating" report at some
point.

**The API response cache** (`app/Support/ApiCache.php`) caches successful GETs. It
is deliberately driver-agnostic — only `get`/`put`/`increment`, never
`Cache::tags()`, because tags are unsupported on the `database` and `file` stores
and this host may not have Redis. Point `CACHE_STORE` at redis and it gets faster
with no code change.

Invalidation is a **global version counter**, not per-model. Bumping it orphans
every key at once. Per-model counters would mean mapping every model to the
endpoints it appears in, and one missed mapping serves stale content forever — a
far worse failure than the few queries it costs to repopulate everything.

**Data migrations bypass it.** They write with the query builder, which fires no
model observers, so nothing invalidates. `app:deploy` clears the cache explicitly
for that reason.

**The frontend caches too.** Fetches originally used `cache: 'force-cache'` with no
expiry, so a response was held **indefinitely** — only the revalidation webhook or
a redeploy could update it. That webhook failed more than once (the deploy crashed
before reaching it; it is skipped entirely when no frontend token is configured).
There is now a five-minute revalidate window as a backstop, so the two cannot
drift apart silently.

**Frontend resilience.** Every fetch goes through `safely()`, which retries
transient 5xx (three attempts, 300ms then 900ms, never retrying a 404) and then
degrades to an empty state rather than throwing. A backend outage makes the site
thin, not broken. `sitemap.xml` and `llms.txt` also revalidate, because they were
prerendered once with no expiry — a backend blip during a build would have frozen a
six-page sitemap indefinitely.

## Design history

The look changed three times and it is worth knowing why, because it caused
confusion.

The Payload-era design was the reference the client liked. A later "polish pass"
changed five block layouts, removed the uppercase eyebrow labels above every
section, and dropped the numbered `01`–`06` service cards. The client asked for the
earlier look back, pointing at a still-live copy of it.

Restoring it was a **port, not a revert** — the components had been rewritten for
the Laravel data shape, so checking the old files back in would have broken them.
Notably, the eyebrows had never been lost from the data: the API had been sending
them the whole time and the frontend had simply stopped rendering them.

Gold was introduced as a brand accent, then partly displaced again when the
reference look was restored: the hero eyebrow and the stat figures went back to
blue and the brand gradient. Gold remains elsewhere. The two requests genuinely
conflict on those elements.

The logo is the mark alone, no wordmark. `icon-color.png` was cropped from
`logo-color.png` because the pre-existing icon-only files are charcoal and white
and would have lost the brand blue on a white header. The social share image
deliberately keeps the wordmark — a link preview showing only an abstract mark
tells nobody who it is from.

## The services restructure

Ten services became four, each listing the old names as what it covers:

| Service | Covers |
|---|---|
| Communications Strategy | Strategic Communications, Communication Advisory, Reputation Management |
| Brand Positioning | Brand Consulting, Founder Branding |
| Content & Storytelling | Content Strategy, Copywriting, Content Writing |
| Digital Marketing | Social Media Management, Marketing Strategy, Digital Marketing |

Three tables carry foreign keys into `services`, so every row was remapped onto its
new parent **before** any old service was deleted. Deleting first would have broken
the constraint or silently detached which service a case study or enquiry was
about.

Nine service URLs disappeared. They are **301 redirects** in `next.config.ts` to the
service that absorbed each one, so the ranking those pages earned carries across
rather than 404ing.

## What is real and what is still placeholder

Be careful here: the site mixes both.

**Real.** The four services and their copy. Six confirmed clients on the client
wall (Biografrica, The Perfumes Room, Energia, Dynamite Agency, Unity Key Group,
Infuzed). Four client testimonials from the founder's portfolio and LinkedIn.
Contact details: `hello@fastora.africa`, WhatsApp `+234 703 814 7969`, "Nigeria ·
Remote · Africa".

**Still placeholder, and visible to the public.** The two case studies are
invented — "Lumen Skincare" and "Northbound Logistics", with fabricated results
like "+212% engagement in 90 days". They sit in the Featured Work section next to
real testimonials, which makes the contrast conspicuous. The client's content
document names the real ones (Biografrica, Infuzed, Naturals by Jelique, SARMLife
Digital School) and defers the details. **Replace or unfeature these before
promoting the site.**

Also outstanding: "How this looked in practice" is empty on all four service pages,
and testimonial avatars are null — the portfolio has photographs but they are not
in this repo, and the logo is not a face.

One editorial note: the testimonials were written about the founder by name and the
client asked for that to read as "Fastora". That substitution is the only edit, but
the quotes no longer match their sources verbatim. Four supplied reviews were
deliberately excluded — reasons are recorded in `database/data/testimonials.php`,
including one from a self-described friend and colleague rather than a client.

## Bugs worth remembering

Patterns that recurred, so they can be recognised faster next time.

**"The deploy succeeded but nothing changed."** Almost always `app:deploy` not
having been run, or a cache layer. Check the API directly with `curl` before
touching the frontend — it isolates backend from frontend in one step.

**An optional step killed a required one.** `storage:link` was called near the end
of `app:deploy` with a comment claiming it was harmless if unsupported. It was
fatal. Migrations had already run, so the deploy did its job and still reported
failure. Anything genuinely optional now runs last and is wrapped.

**Testing the wrong layer.** The consultation form's `kind`, `preferredTimes` and
`timezone` were silently dropped for a whole session because the Next.js proxy
built an explicit payload that omitted them. The backend had accepted them all
along — it had only ever been tested *directly*, never through the proxy a browser
actually uses. Test the path the user takes.

**A filter that outlived its reason.** `PageResource` dropped any repeater row whose
media failed to resolve, correct when a logo was mandatory. Once the client wall
gained a name-only fallback, that filter silently discarded all six confirmed
clients: the database held them, the API returned an empty list.

**A relationship that could not exist.** Page-layout images are bare ids in a JSON
column, not Eloquent relationships, but used the relationship-based picker. Every
page-edit screen 500'd — so "I cannot edit the page text" was a crash, not a
missing feature.

**Picking an image by "first row" gives you the logo**, because the logo is seeded
before the photography. That is how the logo ended up standing in for real imagery
across the site. The selector now excludes brand assets and hero composites.

## Security posture

An audit against a review checklist found, and fixed: no rate limit on the only
unauthenticated write endpoint (now 5/min per IP), CORS answering `*` while
allowing POST (now origin-restricted; reads stay open deliberately), unbounded
input length, no CRLF stripping on values interpolated into email headers, and a
non-constant-time token comparison on the revalidation webhook.

Checked and clean: no secrets in the client bundle, `.env*` ignored in both repos
with no `.env` ever committed, no hardcoded credentials, and the public API exposes
only published content. All nine admin resources have role-based policies; there is
no per-user ownership model, because every panel user is trusted staff and all
content is company-owned.

**One outstanding item: the seeded admin password is committed in the seeder.** Both
repos are private, but it is a live credential for the admin panel. Change it in
the panel and treat the committed value as burned.

## Known gaps

- Homepage and services-index copy from the client's content document is not yet
  applied; the service pages are.
- The two invented case studies are still public. See above.
- Some frontend copy is still hardcoded (button labels, form field labels) rather
  than CMS-driven.
- `predis` is not installed, so `CACHE_STORE=redis` would fail. Adding it means
  running Composer locally and committing `vendor/`.
- Geo-based currency switching exists in the code but is hidden from the header.
- The consultation form asks for preferred times rather than offering slots. That
  was deliberate: this app cannot see the calendar those sessions live in, so a
  slot picker would offer times already taken. Real self-serve booking wants an
  embedded scheduler with two-way calendar sync.
