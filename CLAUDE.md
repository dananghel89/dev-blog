# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

**Dev Blog**: a personal developer blog (Laravel 12, PHP 8.4, Livewire 4). The public site uses the **Blogy** theme by Untree.co. It started from the Laravel Livewire starter kit, but the kit's auth and settings (Fortify, Flux) were removed: there is **no login or admin yet**. A Filament admin is planned (finish plan); until then, posts come from the seeders.

## Commands

```bash
composer setup            # first-time: install deps, copy .env, key:generate, migrate, npm install + build
composer dev              # runs artisan serve + queue:listen + pail (logs) + vite concurrently
composer lint             # pint --parallel (auto-fix code style, "laravel" preset)

npm run build             # compile resources/scss/app.scss + resources/js/app.js into public/build (needed for the site)
npm run dev               # Vite dev server with hot reload

php artisan test                                             # whole suite (green)
php artisan test tests/Feature/Site/SearchTest.php           # single file
php artisan test --filter="search needs at least two"        # single test by name

php artisan migrate:fresh --seed   # rebuild the local MySQL DB (dev_blog) with Category/Tag/Post seed data
```

- Local DB is **MySQL 8.3 on WAMP** (database `dev_blog`), site at `http://dev-blog.test`. `config/database.php` forces `'engine' => 'InnoDB'`, because WAMP's MySQL defaulted to MyISAM, whose 1000-byte key limit breaks the `utf8mb4` unique indexes and which ignores foreign keys. Back up with `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysqldump" -u root dev_blog > ~/Downloads/backups/dev-blog/dev-blog-backup-YYYYMMDD.sql` (not on the PATH).
- **Backups and other files kept outside the repo** (DB dumps, git bundles, route/package baselines, scratch clones) go in `~/Downloads/backups/dev-blog/` (`C:\Users\Dan Anghel\Downloads\backups\dev-blog`), never next to the repo. The repo lives in `C:\Users\Dan Anghel\Develop\Projects\dev-blog` (moved from `Develop\Workspace` on 2026-11-05).
- Tests use in-memory SQLite (`phpunit.xml`), with `RefreshDatabase` applied to everything under `tests/Feature` (via `tests/Pest.php`). **Never remove the `DB_CONNECTION`/`DB_DATABASE` lines from `phpunit.xml`**: tests would then hit `dev_blog` and wipe it. `tests/TestCase.php` calls `withoutVite()`, so tests don't need `npm run build`. Because tests run on SQLite and the app on MySQL, raw SQL must work on both (e.g. `LIKE … ESCAPE '!'` in the search component).
- `MAIL_MAILER=log` locally. Contact-form emails go to `storage/logs/laravel.log`. The recipient is `config('project.contact_email')` (`CONTACT_EMAIL` in `.env`).

## Architecture

**Routes and pages.** `routes/web.php`. Page views are plain Blade in `resources/views/{pages,blog,errors}` and extend `layouts/app.blade.php`. The controllers mostly just resolve the page: `HomeController` builds the home sections; `BlogController` resolves the post, category or tag and its meta data.

**Livewire components** (single-file, in `resources/views/livewire/`; the only component location, see `config/livewire.php`):
- `blog.post-list`: paginated published posts for the blog index, a category or a tag (`?page=N` in the URL)
- `blog.search`: live search, `?q=` in the URL, minimum 2 characters, `%`/`_` matched literally
- `contact-form`: validation, sends `ContactFormMail`, rate limit of 3 messages per 10 minutes per IP

Paginating components return `'vendor.pagination.blogy-livewire'` from `paginationView()`. `resources/views/pages` holds plain Blade views, not Livewire pages (the `pages::` namespace was removed from the config).

**Frontend.** Vite + Sass, no CDNs. `resources/scss/app.scss` = the theme variables + Bootstrap 5.3 (npm) + the Blogy partials in `resources/scss/theme/` + project additions in `resources/scss/app/`. `resources/js/app.js` = Bootstrap JS, the Work Sans font (Fontsource), Bootstrap Icons, AOS, and the theme's navbar and preloader scripts in `resources/js/theme/`. Shared header, footer and sidebar data comes from view composers in `AppServiceProvider` backed by `App\Support\SiteData` (`once()`-cached queries). `Post::imageUrl($ratio)` returns the featured image or a local placeholder (`public/images/placeholders/`).

**Theme license (important).** Blogy is CC BY 3.0 (https://untree.co/license/). **Never remove the Untree.co credit link in `partials/footer.blade.php`** (`ThemeCreditTest` checks it on every page). Keep the `/*! … Untree.co … */` headers in the theme SCSS/JS files; `vite.config.js` sets `esbuild.legalComments: 'inline'` so they survive the build. Never commit the original template files (demo `*.html`, demo images, IcoMoon/Flaticon fonts, the bundled vendor copies).

**Domain model.** `Post` belongs to `Category` and is many-to-many with `Tag` (through `post_tag`). Posts, categories and tags use route-model binding by `slug`. `Post`, `Category` and `Tag` use `App\Models\Concerns\HasUniqueSlug`: a missing slug is generated from `slugSource()` and made unique (`title`, `title-2`, ...). Visibility always goes through `Post::published()` (`is_published = true` AND `published_at <= now()`), including `BlogController::show` (drafts and scheduled posts → 404). Lists order by `published_at`, and every count is of published posts only.

**Seed data.** `CategorySeeder` creates the 8 categories from `CategoryFactory::COLORS` with clean slugs (`named()` state); `PostSeeder` creates 40 posts with unique titles, 5 per category, without images (views show local placeholders). The default `CategoryFactory` keeps a random slug suffix on purpose, so tests can create any number of categories. Never pass a nullable value to an inline `@section('x', $value)`: Blade then opens a section that is never closed.

**Site identity.** Name, tagline, author, contact email and social URLs come from `config/project.php`, backed by `.env` (`APP_NAME`, `BLOG_TAGLINE`, `BLOG_AUTHOR`, `CONTACT_EMAIL`, `SOCIAL_*`). The repo holds only neutral defaults; real values go only in the local `.env`. Apart from `APP_NAME`, these keys are optional and deliberately not in `.env.example` (the README lists them). Views read `config('project.*')`, never hard-coded names. `partials/social-links.blade.php` renders only the networks with a URL (footer, About, Contact). `public/favicon.ico`, `favicon.png`, `apple-touch-icon.png` and `public/images/default-og.png` (1200×630, the `og:image`/`twitter:image` fallback) are the site's own icon and were generated once with PHP GD outside the repo; the source icon is not committed. About shows `public/images/avatar.jpg` when it exists, else the placeholder avatar.

**Production defaults** (`AppServiceProvider`): `CarbonImmutable` dates, destructive DB commands blocked in production, and strict password rules in production only.

## Planned work

Planned work lives in `docs/`, in this order:
1. `blogy-theme-plan.md` (priority 1, phases 0–8 done): the Blogy theme, Vite + Sass, the Livewire components.
2. `project-review-fix-plan.md` (done): bug fixes, Pest suite, removal of Fortify/Flux.
3. `branding-assets-plan.md` (done): identity in `config/project.php`, own favicon, default OG image.
4. `github-migration-plan.md` (done): Bitbucket → public GitHub, history rewritten to the noreply identity and scrubbed of the old brand.
5. `laravel-13-upgrade-plan.md`: Laravel 13, Livewire 4.4, Vite 8, Pest 5.
6. `finish-project-plan.md`: Filament 5 admin, Markdown posts, double-opt-in newsletter, moderated comments, settings, feeds.

Each plan works directly on `master`, one commit per phase, and only after the user approves. Commit messages are one-line `type: description`.

**Repository.** Public on GitHub: `origin` = `https://github.com/dananghel89/dev-blog.git`, branch `master`. Commits use the noreply identity `7040327+dananghel89@users.noreply.github.com`, picked up automatically from `~/.gitconfig-github` (an `includeIf` on GitHub remotes); check `git config user.email` before committing. Never commit a real email address, the old brand, `.env`, or original Blogy template files: everything pushed is public.

## Known gaps (planned)

- No login or admin: Filament 5 in the finish plan.
- No privacy, terms, sitemap, RSS or newsletter: their routes were removed (real 404s) and are rebuilt in the finish plan.
- Post content is seeded HTML printed with `{!! !!}`: switches to Markdown with raw HTML stripped in the finish plan.
