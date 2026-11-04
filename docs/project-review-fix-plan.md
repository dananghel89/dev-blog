# Project review and fix plan

Status: **Draft – not started**
Created: 2026-11-04
Runs **second**, after [blogy-theme-plan.md](blogy-theme-plan.md) (priority 1, added 2026-11-04). Then [branding-assets-plan.md](branding-assets-plan.md), then [github-migration-plan.md](github-migration-plan.md), then [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md), then [finish-project-plan.md](finish-project-plan.md).

## 1. Scope and decisions

| Topic | Decision |
|---|---|
| Stack during this plan | Laravel 12.51, PHP **8.4** (`php`, global `composer` 2.10), Pest 4. WAMP Apache already runs PHP 8.4.26, so no version switch is needed |
| What gets fixed | Everything that is **wrong**: bugs, security issues, broken pages, misleading UI, dead code, code style |
| Unfinished features | **Not built here.** They are made to *fail cleanly* (hidden form, removed route, real 404) and listed in the backlog (section 6). The finish plan builds them with Filament |
| Starter-kit leftovers | **Removed.** The admin will be Filament (finish plan), which brings its own login, password reset and MFA. So Fortify, Flux, the starter-kit settings routes and auth tests go. `livewire/livewire` **stays**: the theme plan uses it for the public components, and Filament needs it |
| Frontend | Vite + Sass with Bootstrap 5.3 and the Blogy theme, all from npm and with no CDNs. This is delivered by [blogy-theme-plan.md](blogy-theme-plan.md), which runs before this plan |
| Database | Local **MySQL 8.3** (WAMP), database `dev_blog`, **InnoDB** (switched from SQLite on 2026-11-04; `config/database.php` forces `'engine' => 'InnoDB'`, and WAMP's `my.ini` now defaults to InnoDB). Data disposable: schema fixes go **directly into the original migrations**, followed by `migrate:fresh --seed` (after a backup). **Tests stay on SQLite in-memory** (`phpunit.xml`) |
| Way of working | Every fix starts with a failing test, then the code change, then a green suite. Each phase is one commit (with your approval) |
| Commit messages | One line, `type: description` (`fix:`, `feat:`, `test:`, `refactor:`, `chore:`, `docs:`, `style:`). The GitHub migration keeps these messages as they are |

## 2. Environment rules

- PHP: plain **`php`** (8.4.26). Composer: global **`composer`** (2.10.3).
- Tests are safe: `phpunit.xml` sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`, so `RefreshDatabase` never touches the `dev_blog` MySQL database.
- > ⛔ **Never remove or comment out those two `phpunit.xml` lines.** Without them, tests would use the `.env` MySQL connection, and `RefreshDatabase` would **wipe `dev_blog`**.
- Back up the local DB before Phase A: `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysqldump" -u root dev_blog > ../dev-blog-backup-YYYYMMDD.sql` (mysqldump isn't on the PATH; restore with `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysql" -u root dev_blog < ../dev-blog-backup-YYYYMMDD.sql`).
- Because tests run on SQLite but the app runs on MySQL, every phase also runs `php artisan migrate:fresh --seed` on MySQL. That catches MySQL-only problems: key lengths, strict mode, foreign keys, JSON columns.
- The local site is `http://dev-blog.test` (WAMP vhost and hosts entry checked 2026-11-04).

## 3. Findings

How each finding was confirmed:
- **Reproduced:** confirmed on 2026-11-04 by rendering every route against a freshly migrated and seeded in-memory SQLite DB (the local DB was not touched). None of these findings depend on the database engine, so they also apply on MySQL.
- **Code:** certain from reading the code.
- **Browser:** must be confirmed in the browser during the fix.

### Effect of the Blogy theme plan (runs first)

The findings were recorded on the old design. The theme plan rewrites every public view and moves the forms and lists into Livewire components, so some findings are already fixed when this plan starts. **Re-check each one in Phase A and mark it done instead of fixing it twice:**

| Finding | After the theme plan |
|---|---|
| R05 | The view part (links via `$category->url`) is done; the controller query fix (published-only counts, one query) stays here |
| R09 | Done: rate limit in the `contact-form` Livewire component (theme plan, Phase 6). Only verify |
| R13 | Mostly done in the `blog.search` component (min 2 characters, `%`/`_` escaped). Content matching HTML markup stays until Markdown (finish plan) |
| R14, R21 | The views were rewritten: check whether the issue still exists in the new markup |
| R17 | Done: Tailwind and the Flux CSS were removed from Vite. Only the `livewire/flux` package removal is left (Phase B) |
| R18, R19, R20 | Done: no jQuery, no `script.js`, no dark mode, header/footer data from a view composer |
| Phase C tests | Mostly delivered by the theme plan's Phase 1 (`tests/Feature/Site/`); this plan only adds to them |

Two suspicions were checked and **dropped** because they turned out not to be bugs:
- The contact mail sets the recipient both in `Mail::to()` and in the Mailable's envelope, but Laravel de-duplicates it: only one mail goes out.
- `{!! $post->content !!}` prints raw HTML, but posts only come from seeders today, so there is no untrusted input yet. The finish plan switches posts to Markdown with raw HTML stripped.

### High

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R01 | Security › Auth | `config/fortify.php:146-150`, `FortifyServiceProvider` | Fortify registration is enabled: **anyone can create an account** with `POST /register` (a user row was created in the probe). The login, register, reset and verify pages themselves return **500** because their `pages::auth.*` views were deleted | Reproduced |
| R02 | Public › Footer | `routes/web.php` (privacy, terms, sitemap), `partials/footer.blade.php:45` | `/privacy`, `/terms` and `/sitemap.xml` return **500** (`View [pages.privacy] not found`, etc.). The Privacy link is in the footer of **every** page | Reproduced |
| R03 | Tests | `tests/Feature/{Auth,Settings}/*`, `DashboardTest.php` | **23 of 33 tests fail.** They test starter-kit pages that were removed (`dashboard`, `settings/*`, `pages::auth.*`); `routes/settings.php` isn't loaded. The blog itself has **no tests** apart from `GET /` → 200 | Reproduced |
| R04 | Seeders | `PostFactory.php` (`category_id => Category::factory()`), `CategoryFactory.php`, `CategorySeeder.php` | Every seeded post creates **its own new category**, so a fresh seed has **58 categories** instead of 8, with only 8 distinct names (e.g. several "PHP"). `CategorySeeder` picks names at random, so some names repeat and others are missing. Slugs get random numbers (`php-123`). Post titles come from a list of 10, so all 50 posts share 10 titles | Reproduced |
| R05 | Public › Home | `HomeController.php:16-21`, `pages/home.blade.php:174` | The "categories" block uses hard-coded names and builds links with `Str::slug($name)`. **Every link 404s**: `/blog/category/php`, `/mysql`, `/htmlcss` (no such category), `/javascript`. The counts include unpublished posts, and each one is a separate query | Reproduced |

### Medium

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R06 | Blog › Scheduled posts | `BlogController.php:31` | `show()` checks only `is_published`. A post with a **future** `published_at` is hidden from lists but readable at its URL | Code |
| R07 | Blog › Counts | `BlogController.php:19, 24`, `partials/header.blade.php:81-86`, `Category`/`Tag::getPostCountAttribute` | Category and tag counts (`withCount('posts')`) include drafts and scheduled posts. The `post_count` accessors run one query per item (N+1) | Code |
| R08 | Mail › Contact | `emails/contact-form.blade.php:265, 291, 311-314` | The message is escaped twice: `{{ nl2br(e(…)) }}` prints a literal `&lt;br /&gt;` instead of line breaks. The reply `mailto:` subject isn't URL-encoded, so a subject containing `&` is cut off. The footer social links are `href="#"` | Reproduced |
| R09 | Security › Forms | `ContactController::send`, `routes/web.php` | No rate limiting on the contact form: every submit sends an email (spam / mail flooding) | Code |
| R10 | Contact › Recipient | `ContactController.php:26`, `ContactFormMail.php:34` | The recipient `hello@example.com` is hard-coded in two places | Code |
| R11 | Public › Newsletter | `pages/home.blade.php:198-213, 286-316`, `NewsletterController` | The form **never submits**: inline JS cancels it and shows "Subscribed!" after a timeout. The controller saves nothing either. `GET /newsletter/confirm/{anything}` says "Email confirmed successfully!" for any token | Reproduced |
| R12 | Config | `.env`, `.env.example` (uncommitted change) | `APP_URL=http://dev-block.test` (typo). The vhost is `dev-blog.test`, so generated absolute URLs (`og:url`, share links, mail links) point to a host that doesn't exist | Code |
| R13 | Blog › Search | `BlogController.php:66-80`, `blog/search.blade.php` | An empty `q` returns **all** posts (`LIKE '%%'`). `%` and `_` in the query aren't escaped. Content search also matches HTML markup (`q=h2` matches every post) | Code |
| R14 | Public › Home | `pages/home.blade.php:23` | With no published posts, the second hero button links to `route('blog.show', '#')` → `/blog/%23` → 404 | Code |
| R15 | Blog › Slugs | `Post.php`, `Category.php`, `Tag.php` (`creating` hooks) | The slug is `Str::slug($title)` with no uniqueness check, so a second post with the same title throws a unique-constraint error (500) | Code |

### Low

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R16 | Starter kit | `app/Actions/Fortify/*`, `app/Concerns/*`, `app/Livewire/Actions/Logout.php`, `routes/settings.php`, `FortifyServiceProvider`, `config/fortify.php`, `2025_08_14_…_add_two_factor_columns_to_users_table.php`, `User` (`TwoFactorAuthenticatable`, `initials()`), `composer.json` (`laravel/fortify`, `livewire/flux`, `livewire/livewire`) | Dead code from the starter kit after its views were deleted (also part of R01) | Code |
| R17 | Frontend toolchain | `vite.config.js`, `package.json`, `resources/css/app.css` | Vite + Tailwind + Flux CSS are built but never loaded: the layout uses CDN Bootstrap and `public/css/styles.css`. `package.json` also pins Linux-only `optionalDependencies` (`@rollup/rollup-linux-x64-gnu@4.9.5`, …). **Fixed by the theme plan, Phase 2** | Code |
| R18 | Public › Layout | `layouts/app.blade.php:104, 107` | Loads jQuery 3.7.1 from a CDN, but nothing uses it. Loads `public/js/script.js`, which is **empty** | Code |
| R19 | Public › Theme | `layouts/app.blade.php:110-142` | The light/dark script runs at the end of `<body>`, so a light-mode visitor sees a dark flash on every page load | Browser |
| R20 | Public › Layout | `partials/header.blade.php:81-86`, `partials/footer.blade.php:56-58` | Inline `@php` queries in partials run on every page. The footer's "Popular Categories" are actually the 5 **latest** categories | Code |
| R21 | Public › Categories | `blog/show.blade.php:16-18`, `partials/footer.blade.php:63` | The badge text colour is decided by `$category->name === 'JavaScript'` in two places, so any other light category colour gets white text | Code |
| R22 | Code style | 28 files | `pint --test` (Laravel preset) fails: blank-line whitespace, import order, trailing commas, brace placement | Reproduced |
| R23 | Docs | – | There is no `README.md` | Code |
| R24 | Config › Database | `.env.example:23-28`, `composer.json:77`, `database/database.sqlite` | The project moved to MySQL (2026-11-04), but `.env.example` still says `DB_CONNECTION=sqlite` (MySQL keys commented out), so a fresh setup gets SQLite. `post-create-project-cmd` still touches `database/database.sqlite`, and the old SQLite file is left in `database/` (git-ignored). The InnoDB fix in `config/database.php` is not committed yet | Code |

Identity and content placeholders ("Your Name", `href="#"` social links, the missing default OG image, the Laravel favicon, `APP_NAME=Laravel`) are handled in [branding-assets-plan.md](branding-assets-plan.md), not here.

## 4. Phases

### Phase A – Safety first (no behaviour change)

1. Work directly on `master`: commit and push each phase only after you confirm it (I show the files and message first), with no feature branch. Back up the DB (section 2).
2. If the theme plan's Phase 0 hasn't already handled them, commit or discard the uncommitted changes first:
   - `package-lock.json` (name `dev-blog` → `dev-blog`)
   - the `.env.example` `APP_URL` line, which is fixed in R12
   - `config/database.php` `'engine' => 'InnoDB'`: **commit it** (`fix: force InnoDB for MySQL tables`)
3. **R24:**
   - `.env.example` → `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=dev_blog`, `DB_USERNAME=root`, `DB_PASSWORD=` (no real password)
   - remove the `database.sqlite` line from `composer.json` `post-create-project-cmd`
   - delete the leftover `database/database.sqlite` (copy it outside the repo first if you want to keep it)
   - check that `.gitignore` still ignores `*.sqlite*`; the test DB is in memory, so no file is created
4. **R12:** `APP_URL=http://dev-blog.test` in `.env`. In `.env.example`, set `APP_URL=http://localhost`, the skeleton default, which keeps local hostnames out of the public repo.
5. Record the baseline: `php artisan route:list > ../routes-before.txt` and `php artisan test` (23 failures expected; this is the "before" state).

### Phase B – Remove the starter-kit leftovers (R01, R03, R16)

1. **Tests:** delete `tests/Feature/Auth/`, `tests/Feature/Settings/` and `tests/Feature/DashboardTest.php`.
2. **Fortify:** delete `app/Providers/FortifyServiceProvider.php` (and its line in `bootstrap/providers.php`), `config/fortify.php`, `app/Actions/Fortify/`, `app/Concerns/`, then `composer remove laravel/fortify`.
3. **Flux:** delete `app/Livewire/Actions/Logout.php` and `routes/settings.php`, then `composer remove livewire/flux`. `livewire/livewire` stays (theme plan, Filament).
4. **User model:** remove `TwoFactorAuthenticatable` and `initials()`. Delete the `add_two_factor_columns_to_users_table` migration; this is safe because the DB is disposable (section 1). `UserFactory` loses its two-factor state, if it has one.
5. **Vite toolchain:** already replaced by the theme plan. Check that `npm run build` still passes after removing Flux; the theme plan already dropped the `flux.css` import.
6. `composer.json`: set `name` to `dananghel/dev-blog` and the `description` to the blog. The final name comes from the branding plan.
7. `php artisan optimize:clear` → `php artisan migrate:fresh --seed` → `php artisan route:list`. Expected difference from the baseline: every Fortify route is gone, and `/login` and `/register` → 404.
8. `php artisan test` → green (the theme plan's `tests/Feature/Site`, plus `ExampleTest` and the unit test). From now on, the **whole** suite is run.

### Phase C – Test suite for the current, correct behaviour

The theme plan's Phase 1 already delivers most of this suite in `tests/Feature/Site/`, and its Phase 6 adds the Livewire component tests. Here: check that every test below exists, add the missing ones and the factory states, and delete the starter kit's `ExampleTest` (replaced by `HomePageTest`).

1. Conventions: Pest (`test()`/`it()`, datasets), files under `tests/Feature`. `RefreshDatabase` is already applied through `tests/Pest.php`.
2. Factories:
   - `CategoryFactory` gets a `named($name)` state.
   - `PostFactory` gets a `scheduled()` state (`is_published = true`, `published_at` in the future).
   - Both stay valid on their own.
3. Write the tests below. In this phase they cover only behaviour that is **already correct**, so they pass.

| Test | Covers |
|---|---|
| `HomePageTest` | `/` renders; it shows the 3 latest published posts and no drafts |
| `BlogIndexTest` | `/blog` lists published posts, paginated by 10; drafts aren't listed |
| `PostPageTest` | A published post renders its title, category and tags; a draft → 404; an unknown slug → 404 |
| `CategoryTagPageTest` | Category and tag pages list only their published posts; an unknown slug → 404 |
| `SearchTest` | Matches in the title or excerpt are found; drafts are never found |
| `ContactFormTest` | Validation errors; a valid submit sends `ContactFormMail` (`Mail::fake()`) and redirects back with the success flash |
| `StaticPagesTest` | `/about` and `/contact` → 200 |

4. `php artisan test` → all green.

### Phase D – Functional and security fixes (High/Medium)

Each item: add a failing test → fix → green.

| ID | Test first | Fix |
|---|---|---|
| R01 | `AuthRoutesTest`: `POST /register` → 404 and no user created; `/login` → 404 | Done by Phase B. The test guards against registration coming back. Filament's login arrives in the finish plan |
| R02 | `StaticPagesTest`: `/privacy`, `/terms`, `/sitemap.xml` → 404 (not 500); the footer has no Privacy link | Remove the three routes and the footer link. Privacy/Terms pages, the sitemap and the feed are built in the finish plan |
| R04 | `SeederTest`: `migrate:fresh --seed` creates exactly the 8 categories with unique names and clean slugs (`php`, `mysql`, …); every post belongs to one of them | `CategorySeeder` creates the 8 named categories (with the colours from `CategoryFactory`). `PostSeeder` uses `->recycle($categories)`. `CategoryFactory` uses `fake()->unique()` on the name list, with no numeric slug suffix. The seeded post titles get a unique number or come from a longer list |
| R05 | `HomePageTest`: each category link on the home page resolves (200), and the counts include published posts only | `HomeController`: `Category::withCount(['posts' => fn ($q) => $q->published()])->orderByDesc('posts_count')->take(4)->get()`. The view uses `$category->url`, `name` and `posts_count`. This is one query instead of four |
| R06 | `PostPageTest`: a scheduled post → 404 | `show()` checks `Post::published()->whereKey($post->id)->exists()`, or the route binding resolves through the `published` scope |
| R07 | `BlogIndexTest`: category and tag counts ignore drafts | `withCount(['posts' => fn ($q) => $q->published()])` everywhere. Remove the `post_count` accessors and use `posts_count` in the views |
| R08 | `ContactMailTest`: rendering the mail shows `line1<br />` (or a real line break) and no `&lt;br`; the `mailto:` link has an encoded subject | `{!! nl2br(e($data['message'])) !!}` (drop the `white-space: pre-wrap`, or keep pre-wrap and print `{{ $data['message'] }}`, but not both). `?subject={{ rawurlencode('Re: '.$data['subject']) }}`. Remove the `#` social links from the mail footer |
| R09 | `ContactFormTest` (`Livewire::test`): the 4th submit within 10 minutes from the same IP is rejected and no mail is sent | Delivered by the theme plan (`RateLimiter::attempt` in the `contact-form` component). Only confirm the test exists and passes |
| R10 | `ContactFormTest`: the mail goes to `config('blog.contact_email')` | New `config/blog.php` with `'contact_email' => env('CONTACT_EMAIL', 'hello@example.com')`, used by the `contact-form` component. Remove the `to:` from the Mailable envelope. Put your real address only in your local `.env`. Document `CONTACT_EMAIL` in `.env.example` |
| R11 | `HomePageTest`: no newsletter form; `NewsletterRoutesTest`: the three newsletter routes → 404 | The theme plan doesn't port the newsletter section. Remove the three routes and `NewsletterController`. The real double-opt-in newsletter is built in the finish plan |
| R13 | `SearchTest` (`Livewire::test(…)` on `blog.search`): an empty or 1-character `q` shows no results (and a hint); `q=%` doesn't match everything | Delivered by the theme plan's `blog.search` component (min 2 characters, `addcslashes($q, '%_\\')`). Confirm the tests. Content search stays as it is until posts are Markdown (finish plan) |
| R14 | `HomePageTest`: with no posts, there's no link to `/blog/%23` | Render the second hero button only when `$featuredPosts->isNotEmpty()` |
| R15 | `PostSlugTest`: two posts with the same title get `title` and `title-2` | A small shared trait `App\Models\Concerns\HasUniqueSlug` (`slugSource()` returns `title` or `name`). Use it in Post, Category and Tag instead of the three `boot()` overrides |

`php artisan test` → green after every row.

### Phase E – Low-priority fixes and cleanup

| ID | Fix |
|---|---|
| R18, R19, R20 | Done by the theme plan (no jQuery or `script.js`, light only, view composer). Only verify |
| R21 | If the new views still colour category badges with `$category->color`: a `Category::textColor()` accessor picks black or white from the colour's luminance. Otherwise drop the finding |
| R23 | A short `README.md`: what the blog is; requirements (PHP 8.4, MySQL 8 with InnoDB, Node 20+); setup (create the `dev_blog` database, copy `.env.example`, `composer setup`); `composer dev`; `php artisan test` (SQLite in-memory, no MySQL needed); and a note that the admin is planned (Filament) |

### Phase F – Code style (R22)

1. `vendor/bin/pint` (Laravel preset) in **its own commit**, with no logic changes, so the diff is easy to review.
2. `vendor/bin/pint --test` → clean.

### Phase G – Verification

1. `php artisan migrate:fresh --seed` on **MySQL** (local, disposable; backup first), then `php artisan test` → all green. `SHOW TABLE STATUS FROM dev_blog` → every table is `InnoDB`.
2. `php artisan view:cache` → OK, then `php artisan view:clear`.
3. `php artisan route:list` → the same blog routes as before, minus Fortify, newsletter, privacy, terms and sitemap.
4. Browser smoke test on `http://dev-blog.test`:
   - **Home:** latest posts, category links work, no newsletter form, no broken hero button
   - **Blog:** index with pagination; a post page (category badge colour readable, tags, related posts); a draft or scheduled post → 404; category and tag pages with correct counts
   - **Search:** a normal query, an empty query, `%`
   - **Contact:** validation, success, a 4th submit is rate-limited; the mail in `storage/logs/laravel.log` shows line breaks
   - **Layout:** the mobile menu, the footer (no Privacy link, Untree.co credit present), and no external requests in the network tab
   - `/login`, `/register`, `/privacy` → 404

### Phase H – Wrap up

1. Update `CLAUDE.md`:
   - The starter kit is gone; Filament is planned.
   - `config/blog.php` / `CONTACT_EMAIL`
   - The `published` rule, which now also applies to `show()`
   - Remove the "Starter-kit leftovers / known gaps" section
2. Final review of all commits since the start of the plan, with your approval. Then the branding plan can start.

## 5. Bugs discovered while implementing

Add any new findings here (ID, location, problem, decision). Fix them only if they are small and you approve; otherwise move them to the backlog.

| ID | Location | Problem | Decision |
|---|---|---|---|
| – | – | – | – |

## 6. Backlog (unfinished features – not part of this plan)

These items are planned in [finish-project-plan.md](finish-project-plan.md), which runs after the Laravel 13 upgrade.

| Feature | Notes |
|---|---|
| Admin to write posts | There is none: posts only come from seeders. Decided: **Filament** (2026-11-04) |
| Post format | Content is raw HTML printed with `{!! !!}`. Move to Markdown with raw HTML stripped, with code highlighting |
| Featured images | `featured_image` stores external `placehold.co` URLs. Needs an upload to the `public` disk |
| Newsletter | Removed in R11. Decided: own double-opt-in newsletter (2026-11-04) |
| Comments | `blog/show.blade.php:161` shows "Comments Coming Soon!". Decided: moderated comments (2026-11-04) |
| Privacy, Terms, sitemap, RSS | Routes removed in R02 |
| Contact messages | Only emailed, never stored (`ContactController` has a commented-out `ContactMessage::create`). No honeypot |
| About page | `AboutController` hard-codes the tech stack and stats (`posts_written => 42` while the DB has 50) |
