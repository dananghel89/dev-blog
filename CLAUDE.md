# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A personal developer blog (Laravel 12, PHP 8.2+) built on top of the official Laravel **Livewire starter kit**. The public blog was added on top of the kit as plain controllers and Blade views. Most of the kit's auth/settings UI has been removed, but its config and tests are still in the repo (see "Starter-kit leftovers" below).

## Commands

```bash
composer setup            # first-time: install deps, copy .env, key:generate, migrate, npm install + build
composer dev              # runs artisan serve + queue:listen + pail (logs) + vite concurrently
composer test             # config:clear, pint --test (lint check), then php artisan test
composer lint             # pint --parallel (auto-fix code style, "laravel" preset)

php artisan test                                   # run all Pest tests
php artisan test tests/Feature/ExampleTest.php     # single file
php artisan test --filter="returns a successful response"   # single test by name

php artisan migrate:fresh --seed   # rebuild the local MySQL DB (dev_blog) with Category/Tag/Post seed data
```

- Local DB is **MySQL 8.3 on WAMP** (database `dev_blog`). `config/database.php` forces `'engine' => 'InnoDB'`, because WAMP's MySQL defaulted to MyISAM, whose 1000-byte key limit breaks the `utf8mb4` unique indexes and which ignores foreign keys. Back up with `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysqldump" -u root dev_blog > ../dev-blog-backup.sql` (not on the PATH).
- Tests use in-memory SQLite (`phpunit.xml`), with `RefreshDatabase` applied to everything under `tests/Feature` (via `tests/Pest.php`). **Never remove the `DB_CONNECTION`/`DB_DATABASE` lines from `phpunit.xml`**: tests would then hit `dev_blog` and wipe it.
- `MAIL_MAILER=log` locally. Contact-form emails go to `storage/logs/laravel.log`.

## Architecture

**Public blog (the real app).** Routes are in `routes/web.php`. `BlogController` handles index, show, category, tag and search. `HomeController`, `AboutController`, `ContactController` and `NewsletterController` serve the other pages. Views are in `resources/views/{pages,blog,partials,emails}` and all extend `layouts/app.blade.php`.

**Domain model.** `Post` belongs to `Category` and is many-to-many with `Tag` (through the `post_tag` table). Posts, categories and tags all use route-model binding by `slug`. `Post` and `Category` generate the slug from the title or name in a `creating` hook if it is empty. Visibility always goes through `Post::published()`, which requires `is_published = true` AND `published_at <= now()`. `BlogController::show` also returns 404 for unpublished posts. Views rely on the accessors `url`, `formatted_date`, `reading_time` (Post) and `url`, `post_count` (Category).

**Two separate frontend stacks (important):**
- The public blog layout loads **Bootstrap 5.3, Bootstrap Icons and jQuery from CDNs**, plus the static, non-Vite files `public/css/styles.css` and `public/js/script.js`. Dark/light theme switching is an inline script in the layout that toggles `body.light-mode` (saved in `localStorage`). The CSS overrides for that mode are in `public/css/styles.css`. Pagination is set to Bootstrap 5 in `AppServiceProvider` (`Paginator::useBootstrapFive()`).
- `resources/css/app.css` and `resources/js/app.js` (Vite + Tailwind v4 + Flux) are starter-kit assets. The blog layout does **not** use them.
- Style blog pages with Bootstrap classes and `public/css/styles.css`, not Tailwind.

**Production defaults** (`AppServiceProvider`): `CarbonImmutable` dates, destructive DB commands blocked in production, and strict password rules in production only.

## Planned work

Planned work lives in `docs/`, in this order:
1. `blogy-theme-plan.md` (priority 1): the public site moves to the Blogy theme by Untree.co, built with Vite + Sass (Bootstrap 5.3 from npm, no CDNs), with Livewire components for the post list, search and contact form. The theme is CC BY 3.0: **the Untree.co footer credit must never be removed**, and only the adapted theme code may be committed, never the original template files or demo images.
2. `project-review-fix-plan.md`: bug fixes, Pest suite, removal of Fortify/Flux.
3. `branding-assets-plan.md`: identity in `config/project.php`, own favicon, default OG image.
4. `github-migration-plan.md`: Bitbucket → public GitHub, history rewritten to the noreply identity.
5. `laravel-13-upgrade-plan.md`: Laravel 13, Livewire 4.4, Vite 8, Pest 5.
6. `finish-project-plan.md`: Filament 5 admin, Markdown posts, double-opt-in newsletter, moderated comments, settings, feeds.

Each plan works directly on `master`, one commit per phase, and only after the user approves. Commit messages are one-line `type: description`.

## Starter-kit leftovers / known gaps

- `routes/settings.php` exists but is **not loaded**: neither `bootstrap/app.php` nor `web.php` requires it. The `pages::auth.*` and `pages::settings.*` Livewire views that Fortify and the settings routes point to have been deleted, and there is no `dashboard` route.
- Because of this, most tests in `tests/Feature/Auth`, `tests/Feature/Settings` and `DashboardTest.php` currently fail (23 of 33). Only `ExampleTest`, the unit test and some Fortify POST-only tests pass. Don't treat these failures as regressions caused by blog changes.
- Fortify is still registered (`FortifyServiceProvider`, `config/fortify.php`) with registration, password reset, email verification and 2FA enabled.
- `web.php` references views that don't exist yet: `pages.privacy`, `pages.terms` and `sitemap`. The layout's default OG image `public/images/default-og.png` is also missing.
- `NewsletterController` is a stub that only validates input and flashes a message, with no persistence. `ContactController` sends `ContactFormMail` to a hard-coded address. `HomeController` counts posts using hard-coded category names (`PHP`, `MySQL`, `HTML`/`CSS`, `JavaScript`), which must match seeded `Category.name` values.
