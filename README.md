# Fastora backend

The CMS and content API behind [fastora.africa](https://fastora.africa). Laravel 12
with Filament v4 on MySQL.

This is the system of record for every word and image on the website. The public site
is a separate Next.js app that reads from this one over HTTP.

> **New here?** Read [PROJECT-HISTORY.md](PROJECT-HISTORY.md) first. It explains why
> several things in this repo look unusual — a committed `vendor/`, a rewrite rule
> instead of a symlink, content duplicated between a seeder and a migration — and the
> host restrictions behind each. It will save you undoing something load-bearing.

## How the two halves fit together

| | Repo | Lives at |
|---|---|---|
| Frontend | `KngViktor/fastora` | `fastora.africa` |
| Backend (this repo) | `KngViktor/Fastorabackend` | `api.fastora.africa`, admin at `/admin` |

Content flows one way. Editors work in the Filament admin here, this app serves JSON
from `/api`, and the frontend renders it. Saving in the admin also pings the frontend
to refresh its cache.

## Local setup

Requires PHP 8.2+ and MySQL. Node only if you plan to change the admin theme.

1. `cp .env.example .env` and fill in the database credentials.
2. `php artisan key:generate`
3. `php artisan migrate --seed`
4. `php artisan serve`

The admin is then at `http://127.0.0.1:8000/admin`. The seeder creates a super admin
— see `database/seeders/DatabaseSeeder.php` for the address. **Change the seeded
password in the panel before this goes anywhere near production: it is committed to
this repo and is not a secret.**

`composer install` is only needed if `vendor/` is somehow missing. It is committed
deliberately — see below.

## Deploying

The host copies files but runs no commands, so pulling the repo updates PHP instantly
while **nothing touching the database happens on its own.** After every deploy, in
the directory containing `artisan`:

```bash
php artisan app:deploy
```

That migrates, syncs bundled media, clears the API cache, rebuilds caches, and asks
the frontend to revalidate. Safe to re-run: seeding only fires on a database with no
pages, so it cannot overwrite live content.

If the site looks stale after a deploy, this command not having run is the first thing
to check. Worth wiring up as a Hostinger cron job.

## Things not to "fix" without reading the history

- **`vendor/` is committed.** `proc_open` is disabled on the deploy runner, so
  Composer cannot run there. After changing `composer.json`, run
  `composer install --no-dev --optimize-autoloader` locally and commit the result.
- **`public/build` is committed**, for the same reason with npm. After editing
  `resources/css/filament/admin/theme.css`, run `npm run build` and commit the output.
- **The root `.htaccess` is load-bearing.** It routes requests into `public/` because
  the document root is locked, and maps `/storage` onto `storage/app/public` because
  symlinks are unavailable. Both rules have subtleties documented in the history file.
- **`app:sync-media` exists because media cannot travel through git.**
  `storage/app/public` is gitignored, so bundled photography is copied from
  `database/seeders/images/` on every deploy.

## Where seeded content lives

Copy that ships with the project is in `database/data/*.php` — plain files returning
arrays, read by **both** the seeder and the migration that backfills an existing
database. Both paths read the same file so a fresh install and the live site cannot
drift apart. If you add seeded content, put it there rather than inline.

Everything in those files stays editable in the admin; they are a starting point, not
fixed copy.

## Editing content

In the admin at `/admin`:

- **Pages** — Home, About, Contact, Consultation. Built from blocks; each block's
  text, images and lists are editable.
- **Services** — the four services, each with its full page copy under the Page tab.
- **Case Studies**, **Insights**, **Testimonials** — their own sections.
- **Site Settings** — logo, colours, contact details, social links.
- **Enquiries** — contact and consultation submissions, opened read-only.
- **Media** — the image library. Every image field can also upload directly from your
  computer.

Saving triggers a revalidation call to the frontend, so changes appear without a
redeploy.
