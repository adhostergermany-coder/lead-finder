# Lead Finder — Agent Guide

## Stack

- **Laravel 13** (PHP 8.3+) — backend only. The Inertia+React setup (`resources/js/`, `HandleInertiaRequests.php`) is **unused**; all rendering is Blade + Tailwind CSS v4 (CDN).
- **SQLite** default. Session & queue also default to `database` driver.
- **Python 3** — two data-fetch scripts in `scripts/` (OSM Photon + Google Places New API).

## Setup

```powershell
# copy .env, generate APP_KEY, migrate, install npm deps (--ignore-scripts), build
composer run setup
```

The `.env` must have `APP_KEY`. Session migrations must be run (`php artisan session:table` already done).

## Dev server

```powershell
composer run dev
```
Starts: `php artisan serve` + `queue:listen` + `pail` (logs) + `npm run dev` (Vite). All four run concurrently.

## Key commands

| Action | Command |
|---|---|
| Run all tests | `composer run test` (clears config first, then `php artisan test`) |
| Run single test | `php artisan test tests/Feature/ExampleTest.php` |
| Laravel Pint | No config file — runs with defaults. `vendor/bin/pint` |
| Database migrate | `php artisan migrate` |
| Clear config | `php artisan config:clear` |

## Architecture

- **Routes**: 5 routes in `routes/web.php` — `leads.index` (GET /), `leads.show`, `leads.updateQuality`, `leads.updateContact`, `leads.destroy`.
- **Controller**: `LeadController` — single-page list + inline editing. No API controllers.
- **Model**: `Lead` with fields: `company_name`, `category`, `email`, `phone`, `website`, `address`, `area`, `lat`, `lon`, `source`, `rating`, `total_ratings`, `website_quality`, `contact_status`.
- **Data fetching**: `OsmService` calls a Python script via `exec()`. Outputs JSON to `storage/app/`, reads, then deletes.
  - `GOOGLE_PLACES_API_KEY` set → Google Places New API (richer data: phone, website, rating)
  - No key → OSM Photon (limited data, no email/phone/website)
- **Dedup**: `WHERE company_name = ? AND area = ?` before insert.
- **Pagination**: 15 per page, `withQueryString()`.
- **Inline editing**: `PUT /leads/{lead}/quality` and `PUT /leads/{lead}/contact` — both return `{success: true}`.

## Data-fetch scripts

Standalone Python scripts. Dependencies: `requests`. Run via `python scripts/osm_fetch.py --area ... --type ... --out ...` or `python scripts/google_places_fetch.py --area ... --type ... --out ... --api-key ...`.

## Gotchas

- `.npmrc` has `ignore-scripts=true` — `npm install` will **not** run build scripts automatically. Run `npm run build` explicitly if needed.
- The Inertia React app under `resources/js/` is **not wired** to any controller route. Do not assume it renders. The app is 100% Blade.
- `npx concurrently` is required for `composer run dev` (listed as `devDependencies`).
- Tests use in-memory SQLite (see `phpunit.xml`).
- Session driver is `database` — requires the `sessions` table migration (already created).
- Queue driver is `database` — requires the `jobs` table migration (already created). `queue:listen` runs in dev.
