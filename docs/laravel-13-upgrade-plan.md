# Upgrade plan: Laravel 12 → Laravel 13

Status: **Draft – not started**
Created: 2026-11-04

> **Prerequisites:** [blogy-theme-plan.md](blogy-theme-plan.md), [project-review-fix-plan.md](project-review-fix-plan.md) and [branding-assets-plan.md](branding-assets-plan.md) must be finished and merged into `master`, and [github-migration-plan.md](github-migration-plan.md) must be done (repo on GitHub, commits using the noreply identity).
> - The theme plan delivers the Vite + Sass build and the public Livewire components.
> - The review plan delivers the full test suite and removes Fortify and Flux.
>
> This plan assumes all of that is in place.

## 1. Goal and decisions

| Topic | Decision |
|---|---|
| Target framework | Laravel **13.x** (latest: 13.40 on 2026-11-04) |
| Target PHP | **PHP 8.4** (8.4.26). It is already the CLI default **and** WAMP Apache's version, so there are no checkpoints and the site keeps working during the upgrade. Laravel 13 requires PHP ≥ 8.3 |
| App structure | Already the slim Laravel 11+ structure (`bootstrap/app.php`, `bootstrap/providers.php`, no Kernels). Only align with the v13 skeleton |
| Tests | The Pest suite from the review plan (SQLite in-memory) is the safety net for every phase |
| Livewire | **Upgrade within 4.x** (4.1.4 → ^4.4.8) in the framework phase. The installed 4.1.4 only allows Laravel 10–12; 4.4.8 allows 13 and is also Filament 5's minimum. Filament itself is installed by the finish plan, **on Laravel 13** |
| Frontend | **Upgrade the Vite toolchain** (Vite 7 → 8, laravel-vite-plugin 2 → 3, Sass latest 1.x) as its own phase. Bootstrap stays on 5.3 |
| Pest | Upgrade to the latest **Pest 4** in the framework phase, then **Pest 5 / PHPUnit 13** as its own phase |
| Out of scope | Building features (finish plan); Bootstrap version bumps |

### Current state → target

These are the versions after the review plan. Check them in Phase 0, since the review plan removes some packages.

| Package | Current | Target |
|---|---|---|
| php | ^8.2 (run on 8.4.26) | ^8.3 (run on 8.4.26) |
| laravel/framework | 12.51.0 | ^13.0 |
| laravel/tinker | 2.11.1 | ^3.0 (3.0.2 supports L13) |
| livewire/livewire | 4.1.4 | ^4.4.8 (allows L13) |
| laravel/pail (dev) | 1.2.6 | ^1.2.7 (supports L13) |
| laravel/pint (dev) | 1.27.1 | ^1.27 |
| laravel/sail (dev) | 1.53.0 | **remove** (not used with WAMP) |
| nunomaduro/collision (dev) | 8.8.3 | ^8.9.5 (requires L ≥ 13.20 on 13.x) |
| pestphp/pest (dev) | 4.3.2 | ^4.6 (Phase 2) → ^5.0 (Phase 6) |
| pestphp/pest-plugin-laravel (dev) | 4.x | ^4.1 (allows `^13.0`) → ^5.0 (requires L ≥ 13.23, PHP 8.4) |
| phpunit/phpunit (via Pest) | 12.5.8 | 12.5.x → 13.x |
| fakerphp/faker, mockery/mockery (dev) | ^1.23 / ^1.6 | unchanged |
| vite / laravel-vite-plugin / sass (npm) | 7.3 / 2.1 / theme-plan version | ^8.3 / ^3.2 / latest ^1.x (versions as checked for agency-cms on 2026-10-30; re-check with `npm view` in Phase 5) |
| bootstrap, bootstrap-icons, @fontsource/work-sans, aos (npm) | theme-plan versions | unchanged (latest patch only) |

## 2. Environment rules

- `php` = PHP 8.4.26; Composer = global `composer` 2.10.3.
- WAMP Apache already runs PHP 8.4.26, so `http://dev-blog.test` keeps working between phases. `php artisan serve` is not needed.
- Database: local MySQL 8.3 (WAMP), database `dev_blog`, InnoDB. Tests use SQLite in-memory (`phpunit.xml`); never remove those lines, or `RefreshDatabase` would wipe `dev_blog`.

## 3. Strategy

1. **Safety net first:** the review plan leaves `master` with a green Pest suite on Laravel 12. Phase 1 only confirms it.
2. One major version (12 → 13, with Livewire 4.4), then the skeleton alignment, the Vite toolchain and Pest 5, as separate phases.
3. Each phase ends with `php artisan test`, the smoke checklist (section 5) and a commit pushed directly to `master`, only after you confirm it (I show the files and message first).

### Phase overview

| # | Phase | Stack after the phase |
|---|---|---|
| 0 | Preparation and baseline | L12 · LW 4.1 · Vite 7 · Pest 4.3 |
| 1 | Confirm the earlier plans are done | L12 · LW 4.1 · Vite 7 · Pest 4.3 |
| 2 | Laravel 12 → 13 (dependencies, Livewire 4.4) | L13 · LW 4.4 · Pest 4.6 |
| 3 | Laravel 13 breaking changes and config review | L13 |
| 4 | Align with the v13 skeleton | L13 (skeleton-aligned) |
| 5 | Vite toolchain | L13 · Vite 8 |
| 6 | Pest 5 / PHPUnit 13 | L13 · Pest 5 |
| 7 | Final verification | – |
| 8 | Wrap up | – |

---

## 4. Phases

### Phase 0 – Preparation and baseline (nothing changes)

1. Work directly on `master`, with no branch. Mark the starting point with `git tag pre-laravel-13` and `git push origin pre-laravel-13`; this is the rollback point.
2. Back up the MySQL DB: `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysqldump" -u root dev_blog > ~/Downloads/backups/dev-blog/dev-blog-backup-YYYYMMDD.sql` (mysqldump isn't on the PATH; restore with `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysql" -u root dev_blog < ~/Downloads/backups/dev-blog/dev-blog-backup-YYYYMMDD.sql`).
3. Record the baseline:
   - `php artisan --version` → `Laravel Framework 12.x`
   - `php artisan route:list > ~/Downloads/backups/dev-blog/dev-blog-routes-before.txt`
   - `php artisan about > ~/Downloads/backups/dev-blog/dev-blog-about-before.txt`
   - `composer show --direct > ~/Downloads/backups/dev-blog/dev-blog-packages-before.txt`
   - `php artisan test` → all green
4. Run the smoke checklist (section 5) on `http://dev-blog.test` and note anything already broken, so it isn't blamed on the upgrade.

### Phase 1 – Confirm the earlier plans are done

1. `master` contains the finished theme, review, branding and GitHub plans.
2. `composer show laravel/fortify livewire/flux` → not installed; `livewire/livewire` 4.x is installed. `npm run build` passes (Vite + Sass, Blogy theme).
3. `php artisan test` → **all green on Laravel 12**. This is the reference for every later phase.

### Phase 2 – Laravel 12 → 13 (dependencies)

1. `composer.json`:
   - `"php": "^8.3"`
   - `laravel/framework: ^13.0`, `laravel/tinker: ^3.0`, `livewire/livewire: ^4.4.8`
   - dev: `laravel/pail: ^1.2.7`, `laravel/pint: ^1.27`, `nunomaduro/collision: ^8.9.5`, `pestphp/pest: ^4.6`, `pestphp/pest-plugin-laravel: ^4.1`
   - remove `laravel/sail` from `require-dev`
2. `composer update -W`
3. `php artisan --version` → `Laravel Framework 13.x`, then `php artisan test`. Continue with Phase 3 before committing if anything fails.
4. **Livewire 4.1 → 4.4:** read the Livewire 4.x release notes between 4.1 and 4.4 and check:
   - the published `config/livewire.php` keys (theme plan, Phase 5)
   - the bootstrap pagination view
   - `#[Url]` properties in `blog.post-list` and `blog.search`
   - `php artisan livewire:publish --config` into a scratch copy, then diff for new keys

### Phase 3 – Laravel 13 breaking changes and config review

Go through the [13.x upgrade guide](https://laravel.com/docs/13.x/upgrade). These items were already checked for agency-cms on 2026-10-30; re-check each one against this codebase:

1. **Request forgery (high impact):** `VerifyCsrfToken` was renamed to `PreventRequestForgery`. `bootstrap/app.php` has an empty `withMiddleware()`, and nothing references the old class (`grep -rn VerifyCsrfToken app bootstrap config tests` → nothing), so no change is needed.
2. **Session `serialization` → `json`:** adopt it. The only session data is flash strings and old input (and the Filament login later). Active sessions are invalidated once, which is harmless.
3. **Cache `serializable_classes`:** the app caches no PHP objects (the cache store is `database`; nothing calls `Cache::` today), so `false` is safe.
4. **Cache prefix / session cookie name:** `config/cache.php` and `config/session.php` derive them from `APP_NAME`. `APP_NAME` changed to "Dev Blog" on 2026-11-04, so the cookie name already changed once there. Check that nothing hard-codes the old name.
5. **`symfony/polyfill-php85` global functions** (`array_first`, `array_last`): the app defines no global helper functions, so there's no conflict.
6. **Pagination view rename:** applies only to the Bootstrap 3 views. The app uses `Paginator::useBootstrapFive()`. Check that `{{ $posts->links() }}` still renders Bootstrap 5 markup.
7. **`Date::use(CarbonImmutable::class)`** and `DB::prohibitDestructiveCommands()` in `AppServiceProvider`: still supported; check that the seeders and factories don't mutate dates in place (`published_at`).
8. `upsert`, `DELETE … JOIN`, queue events, polymorphic pivots, UUIDs: not used.
9. `php artisan test` → green → smoke test.

### Phase 4 – Align with the v13 skeleton

Use the `laravel/laravel` v13 skeleton as the reference ([compare 12.x…13.x](https://github.com/laravel/laravel/compare/12.x...13.x)).

1. **`config/`:** the starter kit published `app`, `auth`, `cache`, `database`, `filesystems`, `logging`, `mail`, `queue`, `services` and `session`.
   - Compare each with the L13 version (`php artisan config:publish --all --force` into a scratch copy of the repo, then diff).
   - Adopt the new keys, re-apply project values, and **delete any config file that ends up identical to the framework default** (L11+ falls back to the framework's).
   - Keep `config/project.php`, and keep `config/database.php` (it has the project value `'engine' => 'InnoDB'` for the mysql connection; re-apply it if the file is replaced).
2. **`.env.example`:** add the new L13 skeleton keys; keep `DB_CONNECTION=mysql` (with the `dev_blog` keys), `SESSION_DRIVER=database`, `CACHE_STORE=database` and `QUEUE_CONNECTION=database`. Your local `.env` gets the same keys.
3. **`composer.json` scripts:** align `setup`, `dev`, `test`, `post-*` with the skeleton, including the npm steps (`npm install`, `npm run build`, `npm run dev`). Keep `lint` and `test:lint`.
4. **`phpunit.xml`, `bootstrap/app.php`, `bootstrap/providers.php`, `tests/TestCase.php`, `tests/Pest.php`, `public/index.php`, `artisan`:** diff against the skeleton and adopt any changes.
5. `composer dump-autoload` → `php artisan optimize:clear` → `php artisan route:list > ~/Downloads/backups/dev-blog/dev-blog-routes-after.txt`, then diff against the baseline (expected: identical) → `php artisan test` (green) → smoke test.

### Phase 5 – Vite toolchain

1. `package.json` devDependencies: `vite: ^8`, `laravel-vite-plugin: ^3`, `sass` (latest 1.x). Check the current versions with `npm view vite version` etc. Keep Bootstrap 5.3, Bootstrap Icons, Work Sans and AOS (patch updates only).
2. Read the laravel-vite-plugin upgrade notes (2.x → 3.x) and the Vite 8 migration guide. Update `vite.config.js` if the `input`/`refresh` options or the `css.preprocessorOptions` shape changed.
3. Delete `node_modules` and `package-lock.json`, then `npm install`. Node 24.22 meets Vite 8's requirement.
4. `npm run build`: the manifest and both bundles in `public/build`; fonts and icons are emitted; the `/*!` banners (Untree.co, Bootstrap) are still in the output; no new Sass warnings (update `silenceDeprecations` only with IDs the build prints).
5. `npm run dev` with `http://dev-blog.test` → hot reload works on Blade, SCSS and JS; the Livewire components still work (search, contact, pagination).
6. `npm audit` (information only).

### Phase 6 – Pest 5 / PHPUnit 13

Pest 5 requires PHP ^8.4 and PHPUnit ^13.2.4; `pest-plugin-laravel` 5 requires Laravel ^13.23.

1. Read the Pest 5 upgrade guide and note what applies (removed or renamed expectations, changed `pest()` configuration, dataset syntax).
2. `composer.json` dev: `pestphp/pest: ^5.0`, `pestphp/pest-plugin-laravel: ^5.0`. Check that `nunomaduro/collision` ^8.9.5 still matches (it allows `pestphp/pest ^5.0`). Then `composer update -W`.
3. `vendor/bin/phpunit --migrate-configuration` if PHPUnit 13 reports an outdated `phpunit.xml` schema.
4. Fix any deprecations or failures that the guide predicts. Remove the unused `toBeOne` expectation and the `something()` helper from `tests/Pest.php` (starter-kit samples).
5. `php artisan test` → green.

If Pest 5 turns out to block on something outside this project, skip this phase: Pest 4.6 fully supports Laravel 13. Record the reason in section 7.

### Phase 7 – Final verification

1. `composer validate`, `composer audit`, `npm audit` (information only; record the findings).
2. `php artisan test` (green), `php artisan about` (PHP 8.4.26, Laravel 13.x), `php artisan route:list` diff vs baseline.
3. Full smoke checklist (section 5) on `http://dev-blog.test`.

### Phase 8 – Wrap up

1. Update `CLAUDE.md`: Laravel 13, Livewire 4.4, Vite 8, Pest 5 (or 4.6), the trimmed `config/` folder, composer scripts.
2. `vendor/bin/pint --test` → clean (Pint may bring new rules; fix them in their own `style:` commit).
3. Final review of `git diff pre-laravel-13 master`, with your approval. Then delete the tag (`git tag -d pre-laravel-13`, `git push origin :refs/tags/pre-laravel-13`).

---

## 5. Smoke-test checklist (run after every phase)

Public site:
- [ ] `/` renders in the Blogy theme: retro grid, category blocks with working links; styles, Work Sans and Bootstrap Icons load from `public/build` (`npm run build` assets); AOS animations run
- [ ] `/blog`: the Livewire post list, pagination (page in the URL), sidebar categories and tags with counts
- [ ] A post page: content, category badge, tags, related posts, share links using `http://dev-blog.test`
- [ ] Category and tag pages; the Livewire live search (`?q=` server-rendered, typing updates, 1 character → hint, `%`)
- [ ] `/about`, `/contact`: the Livewire contact form validates; a valid submit shows the success alert and logs the mail (`MAIL_MAILER=log` → `storage/logs`); the rate limit triggers on the 4th submit
- [ ] The mobile menu; the Untree.co footer credit on every page
- [ ] A draft or unknown post → 404; `/up` → 200

CLI:
- [ ] `php artisan test` → all green
- [ ] `php artisan about`, `php artisan route:list`, `php artisan migrate:status`
- [ ] `php artisan migrate:fresh --seed` works on MySQL (local, after the backup); all tables are InnoDB

## 6. Rollback

- Code: `git revert --no-edit pre-laravel-13..master` (a new commit, so no force-push), then `composer install`, `npm install` and `npm run build`. To only test the old state, run `git checkout pre-laravel-13`, then the same three commands.
- Database: the upgrade adds no migrations. If anything goes wrong, restore the Phase 0 dump with `mysql -u root dev_blog < ~/Downloads/backups/dev-blog/dev-blog-backup-YYYYMMDD.sql` (full path in Phase 0).
- Each phase is its own commit, so you can also revert only the phases after the last good one.

## 7. Known issues and risks

| Item | Impact | Handling |
|---|---|---|
| Earlier plans not finished | No safety net; Fortify/Flux still installed | Phase 1 blocks the upgrade until they are merged |
| Session serialization / cookie name change | One forced re-session (flash messages lost once) | Expected |
| Pest 5 blockers | Phase 6 can't complete |
| Sass deprecation noise (Bootstrap 5.3 `@import`) | Build warnings only | `quietDeps` + `silenceDeprecations` (theme plan; re-checked in Phase 5) | Stay on Pest 4.6 (supports L13); record it here |
| Filament compatibility | – | Checked 2026-11-04: Filament 5.11.1 requires `illuminate/contracts ^11.28\|^12.0\|^13.0` and `livewire/livewire ^4.4.8` |

Bugs found during the upgrade (fill in while implementing; pre-existing bugs belong in the review plan, section 5):

| Test | Bug | Decision |
|---|---|---|
| – | – | – |

## 8. References

- Laravel 13 upgrade guide: https://laravel.com/docs/13.x/upgrade
- Skeleton diff: https://github.com/laravel/laravel/compare/12.x...13.x
- Pest docs (upgrade guide): https://pestphp.com/docs/upgrade-guide
- Package constraints verified on Packagist (2026-11-04):
  - laravel/framework 13.40.0 (php ^8.3)
  - laravel/tinker 3.0.2, laravel/pail 1.2.7, nunomaduro/collision 8.9.5: all allow L13
  - pestphp/pest 4.6.0 (phpunit ^12.5.16) + pest-plugin-laravel 4.1.0 (L ^13.0)
  - pestphp/pest 5.0.0 (php ^8.4, phpunit ^13.2.4) + pest-plugin-laravel 5.0.1 (L ^13.23)
  - filament/filament 5.11.1 (L 11–13, Livewire ^4.4.8)
  - livewire/livewire 4.4.8 (laravel/framework ^10.15|^11|^12|^13); the installed 4.1.4 allows only up to Laravel 12
