# Plan: switch the public design to the Blogy theme (Vite + Livewire)

Status: **Draft – not started**
Created: 2026-11-04
Priority: **1, runs first.** Then [project-review-fix-plan.md](project-review-fix-plan.md), [branding-assets-plan.md](branding-assets-plan.md), [github-migration-plan.md](github-migration-plan.md), [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md), [finish-project-plan.md](finish-project-plan.md).

> The license part of this plan is a technical checklist based on the published license texts, not legal advice.

## 1. Decisions

| Topic | Decision |
|---|---|
| Theme | **Blogy v1.0.0** by Untree.co (source: `~/Downloads/webs/blogy-v1.0.0`), Bootstrap 5 based. It replaces the current custom dark Bootstrap design on every public page |
| Build | **Vite** (already installed: Vite 7.3, laravel-vite-plugin 2.1). The theme SCSS is compiled with **Sass** on top of **Bootstrap 5.3 from npm**. Tailwind and the Flux CSS import are removed from the Vite pipeline |
| Third-party assets | **Everything comes from npm, bundled by Vite**: Bootstrap 5.3 (SCSS + JS), Bootstrap Icons, Work Sans (Fontsource), AOS. No CDN on the public site; the jsDelivr, Google Fonts and jQuery links are removed |
| Livewire | **Interactive parts only** (decided 2026-11-04). Pages stay Blade, which is fast and SEO-friendly. Livewire 4 components handle: the blog post list (index, category, tag, with pagination), the live search, and the contact form. The newsletter and comment forms are built later as Livewire components in the finish plan |
| Colours / mode | **Light only** (decided 2026-11-04). Blogy's colours (`$primary: #214252`, `$secondary: #4D4C7D`) and its Work Sans typography are adopted as designed. The dark/light toggle and the dark styles are removed |
| Demo images | **Not used** (decided 2026-11-04). None of the theme's photos (`hero_*`, `img_*`, `person_*`, `world-dotted-map.png`) are copied. Posts without an image use local SVG placeholders in the theme's three image ratios |
| Theme sections without data | Not ported: the About "Our Team" and "trusted by 5,000 clients" sections, the sidebar author bio with the stock photo, the fake comment list in `single.html`, the contact "Location / Open hours / Call" cards |
| Repo | Stays **public** on GitHub (decided 2026-11-04). See section 2 for how the license is respected |
| Way of working | Test first, as in the other plans: a safety net of route tests before any view changes, then one commit per phase (with your approval), directly on `master` |

## 2. License: what it allows and how this plan complies

**Sources:**
- https://untree.co/license/ (checked 2026-11-04, plus your screenshot)
- the header of every theme file:
  ```
  Template Name: Blogy · Template Author: Untree.co · License: https://creativecommons.org/licenses/by/3.0/
  ```

| The license says | What this plan does |
|---|---|
| CC BY 3.0: use for personal and commercial projects, change it however you like | The theme is adapted into Blade views and app SCSS/JS |
| **You can't remove the Untree.co backlink from the footer** of every template (unless you pay $19 for a single website) | The footer partial is shared by **every** public page and always renders the credit line from the theme: *"Designed with love by [Untree.co](https://untree.co)"*, plus *"Distributed by [ThemeWagon](https://themewagon.com)"*, kept as in the original footer. The credit is hard-coded in the partial, not a setting, so it can't be switched off by accident. A test checks it on every public page |
| **You can't redistribute the templates** | The repo contains **only the adapted theme code inside the application**, never the template package: no `*.html` demo pages, no demo images, no `favicon.png`, no `prepros-6.config`, no IcoMoon/Flaticon fonts, no copy of the theme's bundled Bootstrap/plugin files. Only Untree.co's own SCSS partials and navbar script are copied, modified, into `resources/` |
| CC BY 3.0 attribution: credit the author, link the license, and mark changes | (1) The `/*! … */` header comment is kept at the top of every SCSS/JS file taken from the theme, with "Modified for Dev Blog" added. Vite's minifier drops these comments by default; `esbuild: { legalComments: 'inline' }` in `vite.config.js` keeps them (found and fixed in Phase 2, 2026-11-04), so the credit is also in the compiled CSS/JS. (2) README "Credits": *Design based on the Blogy template by Untree.co (https://untree.co), licensed CC BY 3.0, modified.* |

The original download stays **outside** the repo (`~/Downloads/webs/blogy-v1.0.0`) and is used only as the reference while converting.

### What is taken from the theme

| Theme file | Goes to | Notes |
|---|---|---|
| `scss/style.scss` | `resources/scss/app.scss` | The import order is adapted to Bootstrap 5.3 (functions → variables → variables-dark → maps → mixins → utilities) |
| `scss/components/_site-navbar.scss`, `_site-base.scss`, `_site-blocks.scss`, `_site-footer.scss` | `resources/scss/theme/` | Unused blocks (team, testimonials, sliders, video lightbox) are removed while converting |
| `js/navbar.js` | `resources/js/theme/navbar.js` | Converted to an ES module (mobile menu clone, sticky nav) |
| `js/custom.js` | `resources/js/theme/init.js` | Only the AOS init and the preloader are kept; tiny-slider/GLightbox init removed (not used) |
| `js/counter.js`, `tiny-slider.js`, `glightbox.min.js`, `flatpickr.min.js`, `css/*`, `fonts/*`, `scss/bootstrap/*`, `images/*`, `*.html` | **not copied** | Bootstrap and AOS come from npm. Bootstrap Icons replace the IcoMoon social icons and the `icofont-close` icon. The theme doesn't use the Flaticon font at all |

## 3. Page mapping

| Theme page | App view | Content |
|---|---|---|
| `index.html` | `pages/home.blade.php` | **Retro layout** (5 latest published posts, featured grid) → two **category blocks** (`posts-entry`: the 2 categories with the most published posts, 5 posts each, "View All" → the category page) → **small grid** (`posts-entry-sm`, the next 4 posts) → **"More posts"** (`post-entry-alt`, the next posts, link to `/blog`) |
| `blog.html` | `blog/index.blade.php` | Inner-page hero ("Blog") + `<livewire:blog.post-list />` + sidebar |
| `category.html` | `blog/category.blade.php`, `blog/tag.blade.php` | Hero with the category/tag name + `<livewire:blog.post-list :category="$category" />` (or `:tag`) + sidebar |
| `search-result.html` | `blog/search.blade.php` | `<livewire:blog.search />` + sidebar |
| `single.html` | `blog/show.blade.php` | `site-cover` with the featured image (or the theme's primary colour) + title + meta (date, reading time, category) → content (`.post-content-body`) → tags → share links → related posts (`posts-entry-sm`) + sidebar. **No comment list/form** until the finish plan |
| `about.html` | `pages/about.blade.php` | Cover + the two `sec-halfs` blocks (your bio and focus; image: placeholder until the branding plan) + `sec-features` with the tech stack (Bootstrap Icons) + stats |
| `contact.html` | `pages/contact.blade.php` | Inner-page hero + email/social info + `<livewire:contact-form />` |
| – | `errors/404.blade.php` | Hero + "Page not found" + search form + latest posts. This is new: today the framework's default 404 page is shown |

**Sidebar** (`blog/partials/sidebar.blade.php`, shared):
- the search box (a GET form to `blog.search`)
- *Popular Posts*: the 3 latest published posts, or by comment count once the finish plan adds comments
- *Categories* and *Tags* with published-post counts

The theme's bio box isn't ported.

**Header:** logo "Dev Blog." in the theme's logo style, with the theme's primary-coloured dot (like "Blogy."; decided 2026-11-04). The menu has Home, Blog, a Categories dropdown (top categories), About and Contact, plus the theme's search form (a GET form to `blog.search`). The mobile menu is built by `navbar.js`.

**Footer:** *About* (short blurb), *Social* (Bootstrap Icons; only configured networks once the branding plan is done), *Company* links (Home, Blog, About, Contact), *Recent Posts* (3 latest), and the **credit line** (section 2).

## 4. Phases

### Phase 0 – Prerequisites and baseline

1. Work directly on `master`; tag the starting point: `git tag pre-blogy` and `git push origin pre-blogy`.
2. Back up the MySQL DB: `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysqldump" -u root dev_blog > ../dev-blog-backup-YYYYMMDD.sql` (mysqldump isn't on the PATH; restore with `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysql" -u root dev_blog < ../dev-blog-backup-YYYYMMDD.sql`).
3. ~~Commit the uncommitted changes~~: **done 2026-11-04** (`e6c1415`, `4d2e9f7`, `f8aab6b`: InnoDB fix, package-lock name, `.env.example` on MySQL and `dev-blog.test`). Check that `git status` is clean.
4. Take screenshots of the current home, blog, post, about and contact pages for comparison (`../dev-blog-before/`).
5. `npm install`, then `npm run build` → it works today (Tailwind + Flux CSS), and this is the reference.

### Phase 1 – Safety net: route tests for the current behaviour

The full suite is red today because of the starter-kit tests (review-plan R03), which the review plan removes. This plan runs only its own folder: `php artisan test tests/Feature/Site`.

1. `tests/TestCase.php`: call `$this->withoutVite()` in `setUp()`, so tests don't need `npm run build`.
2. Tests in `tests/Feature/Site/`. They cover behaviour that is **already correct**, so they pass before and after the redesign:

| Test | Covers |
|---|---|
| `HomePageTest` | `/` → 200; shows the latest published post titles; no drafts |
| `BlogIndexTest` | `/blog` lists published posts; page 2 works; no drafts |
| `PostPageTest` | A published post shows its title, category and tags; a draft → 404; an unknown slug → 404 |
| `CategoryTagPageTest` | Category and tag pages list only their published posts |
| `SearchTest` | A match in the title or excerpt is found; drafts are never found |
| `ContactFormTest` | Validation errors; a valid submit sends `ContactFormMail` (`Mail::fake()`) |
| `StaticPagesTest` | `/about`, `/contact` → 200 |

3. The tests assert **content, not markup** (`assertSee($post->title)`, `assertDontSee($draft->title)`), so they survive the redesign unchanged. In Phase 6, the form tests move from HTTP POSTs to `Livewire::test()`.
4. These tests become the review plan's Phase C suite, which only adds to them.

### Phase 2 – Vite pipeline (Bootstrap SCSS, no Tailwind)

1. **npm:**
   - remove `tailwindcss`, `@tailwindcss/vite`, `autoprefixer` and the Linux-only `optionalDependencies`
   - add `bootstrap@^5.3`, `@popperjs/core`, `bootstrap-icons`, `@fontsource/work-sans` (400/600/700), `aos@^2.3` and `sass` (dev)
   - delete `node_modules` and `package-lock.json`, then `npm install`
2. **`vite.config.js`:**
   - inputs `resources/scss/app.scss` and `resources/js/app.js`; remove the `tailwindcss()` plugin
   - add:
     ```js
     css: { preprocessorOptions: { scss: { quietDeps: true, silenceDeprecations: [/* only IDs the build prints, e.g. 'import' */] } } }
     ```
   - add `esbuild: { legalComments: 'inline' }`: without it, the minifier strips the `/*!` license banners (Untree.co, Bootstrap, Bootstrap Icons) from the build
   - keep `refresh: true` and the `storage/framework/views` watch-ignore
3. **SCSS** (`resources/scss/app.scss`):
   - the theme header comment (`/*! … modified */`), then the theme variables (`$font-family-sans-serif: 'Work Sans'`, `$primary`, `$secondary`)
   - the Bootstrap 5.3 import stack from `bootstrap/scss/…`, then `@import 'bootstrap-icons/font/bootstrap-icons.css'` and `aos/dist/aos.css`
   - then `theme/site-navbar`, `site-base`, `site-blocks`, `site-footer`, plus `app/` for project additions (post content typography, pagination, Livewire loading states)
   - fix the Bootstrap 5.1 → 5.3 differences the build reports (renamed mixins or variables; `$theme-colors` maps)
4. **JS** (`resources/js/app.js`):
   - `import 'bootstrap'` (the bundle with Popper)
   - `import '@fontsource/work-sans/400.css'` (and 600, 700)
   - `import AOS from 'aos'`, plus `./theme/navbar` and `./theme/init`
   - after Livewire DOM updates, re-run AOS: `document.addEventListener('livewire:init', () => Livewire.hook('morph.updated', () => AOS.refresh()))`
   - remove the starter kit's axios bootstrap if nothing uses it
5. Remove `resources/css/app.css` (Tailwind + Flux) and its `@source` lines.
6. `npm run build` → `public/build/manifest.json` with one CSS and one JS bundle, and the fonts and icons emitted as assets. `npm run dev` with `http://dev-blog.test`: hot reload works on Blade, SCSS and JS changes.

### Phase 3 – Layout, header, footer

1. `layouts/app.blade.php` is rebuilt from the theme's `<head>`/`<body>` skeleton:
   - keep the current SEO yields (`title`, `meta_description`, `meta_keywords`, `og:*`, `twitter:*`) and the CSRF meta
   - replace every CDN `<link>`/`<script>` with `@vite(['resources/scss/app.scss', 'resources/js/app.js'])`
   - Livewire's scripts and styles are injected automatically (Livewire 4); check that only one Alpine instance loads
   - the theme preloader (`#overlayer`, `.loader`) and the mobile-menu container
   - flash messages (`success`/`error`) styled as theme alerts, under the header
   - **remove**: the dark/light toggle and its inline script, the jQuery and `public/js/script.js` tags, the Bootstrap/Icons/Google Fonts CDNs
2. `partials/header.blade.php` (section 3): active menu item by route name, and the Categories dropdown fed by a **view composer** (`View::composer('partials.header', …)`, top 6 categories by published-post count). This replaces the inline `@php` query (review-plan R20).
3. `partials/footer.blade.php` (section 3) with the credit line. The *Recent Posts* data comes from the same view composer.
4. `blog/partials/sidebar.blade.php`, `blog/partials/post-card.blade.php` (the `blog-entry` and `post-entry-alt` variants) and `blog/partials/post-meta.blade.php`, so the pages reuse the same markup.
5. **Placeholders:**
   - `public/images/placeholders/post-horizontal.svg`, `post-square.svg` and `post-vertical.svg`, in the theme colours with a code-bracket icon, plus `avatar.svg`
   - a `Post::imageUrl(string $ratio)` helper returns the featured image or the matching placeholder
   - `PostFactory` stops using `placehold.co`: `featured_image = null`. This covers branding-plan Phase 3
6. Tests:
   - `ThemeCreditTest`: a dataset over every public page (`/`, `/blog`, a post, a category, a tag, `/blog/search?q=php`, `/about`, `/contact`, a 404) asserts a link to `https://untree.co` in the footer
   - `LayoutTest`: no request to `cdn.jsdelivr.net`, `fonts.googleapis.com` or `code.jquery.com` in the HTML; the `@vite` tags are present

### Phase 4 – Blade pages

Convert each page following section 3. The rules:
- Keep the theme's classes and structure; replace the demo text with model data. Every link uses `route()`.
- Dates: `$post->published_at->format('M. jS, Y')`, the theme's format.
- Featured images: `style="background-image: url('{{ $post->imageUrl('horizontal') }}')"`, as in the theme, plus `role="img"` and `aria-label="{{ $post->title }}"`.
- The post body keeps `{!! $post->content !!}` for now (seeded HTML). The finish plan switches it to Markdown. The theme's `.post-content-body` styles get additions for `pre`, `code`, `blockquote`, lists and tables.
- The `@push('styles')` blocks with inline CSS in the old views are removed; whatever is still needed moves to `resources/scss/app/`.
- The home "categories" block uses `$category->url`, which fixes the 404 links of review-plan R05 in the view; the controller query fix stays in the review plan.

Order: layout pieces (Phase 3) → home → post page → about → contact (static part) → 404. Run `php artisan test tests/Feature/Site` after each page.

### Phase 5 – Livewire setup

1. `php artisan livewire:publish --config`, then set:
   - `component_locations` → `[resource_path('views/livewire')]` only. `resources/views/components` would mix in anonymous Blade components.
   - `component_namespaces`: keep `layouts`; **remove `pages`**. `resources/views/pages` holds plain Blade views (`home`, `about`, `contact`), not Livewire pages.
   - `make_command` → `type: 'sfc'`, `emoji: false`
   - `pagination_theme` → `'bootstrap'`; the pagination view is published and restyled to the theme's `custom-pagination`
2. Check that `php artisan route:list` and every page still work, and that `view('pages.home')` still resolves to the Blade view.

### Phase 6 – Livewire components

| Component | Behaviour | Tests (`Livewire::test`) |
|---|---|---|
| `blog.post-list` | Props: optional `category` / `tag` model. Uses `WithPagination` (10 per page, page number in the URL with `#[Url]`, so links stay shareable and SEO-crawlable). Published posts only, eager-loading `category` and `tags`. Renders the theme's `blog-entry` list. Empty state: "No posts yet" | Lists published posts only; category/tag filtering; page 2; the empty state |
| `blog.search` | `#[Url(as: 'q')] public string $q`, bound with `wire:model.live.debounce.300ms`. Searches title, excerpt and content of published posts; needs at least 2 characters (shorter → a hint, no query); `%`/`_` escaped. Paginated. The header and sidebar search forms are plain GET forms to `/blog/search?q=…`, so the first render is server-side | `?q=` from the URL fills the input; typing updates results; 1 character → no results; `%` doesn't match everything; drafts never found |
| `contact-form` | Fields `name`, `email`, `subject`, `message`, with the validation rules of `ContactController::send` and the same `ContactFormMail`. On success: the form resets and shows the theme's success alert inline. Loading state: `wire:loading` on the button. Rate limit: `RateLimiter::attempt('contact:'.request()->ip(), 3, …, 600)` → a friendly error (this also delivers review-plan R09) | Validation; success sends the mail and resets the form; the 4th submit → error, no mail |

1. Pages embed the components (section 3). The blog, category and tag controllers only resolve the model and its meta tags; the listing moves into `blog.post-list`. `BlogController::search` returns the page shell only.
2. Remove `ContactController::send` and the `POST /contact` route; `ContactFormTest` moves to `Livewire::test(…)`.
3. Every component has `wire:loading` feedback that matches the theme (button spinner, list opacity).
4. Accessibility: the live search results region has `aria-live="polite"`.

### Phase 7 – Cleanup

1. Delete `public/css/styles.css` and `public/js/script.js`.
2. `grep -rn "cdn.jsdelivr\|fonts.googleapis\|code.jquery\|light-mode\|toggleTheme\|placehold.co" resources app database` → nothing.
3. `public/favicon.*`: still the Laravel files. The branding plan replaces them; **don't** copy the theme's `favicon.png`.
4. Remove the dark-mode CSS leftovers and any unused theme SCSS blocks. Check the CSS bundle size before and after (information only).

### Phase 8 – Verification and documentation

1. `npm run build` → OK, with no Sass errors (deprecation warnings are only allowed if they are silenced on purpose).
2. `php artisan test tests/Feature/Site` → green; `vendor/bin/pint --test` on the changed PHP files.
3. Smoke checklist (section 5) on `http://dev-blog.test`, at desktop and mobile width (Chrome DevTools 375 px).
4. Docs:
   - **`CLAUDE.md`:**
     - the frontend is Vite + Sass (Bootstrap 5.3 + the Blogy theme SCSS in `resources/scss/theme/`)
     - Livewire components live in `resources/views/livewire/` (SFC); `pages/*` are plain Blade
     - **the Untree.co footer credit must never be removed**
     - no CDNs; `npm run build` is needed (tests use `withoutVite()`)
   - **`README.md`** (created in the review plan, or a stub now): the Credits section from section 2.
5. Final review of the plan's commits with your approval; then delete the `pre-blogy` tag.

## 5. Smoke checklist

- [ ] Home: retro grid, category blocks with working "View All", small grid, more posts; placeholders where there's no image; AOS animations run once
- [ ] Header: active item, Categories dropdown, search form; the mobile menu opens and closes (`navbar.js`); sticky behaviour
- [ ] Blog / category / tag: the post list, pagination (page in the URL, back button works), sidebar counts
- [ ] Search: `/blog/search?q=php` renders server-side; typing updates live; 1 character → hint; `%` → no flood
- [ ] Post: cover, meta, content (headings, code blocks, lists), tags, share links, related posts
- [ ] About, Contact: layout; the contact form validates, sends (log), resets, and rate-limits
- [ ] 404 page in the theme
- [ ] Footer credit "Designed with love by Untree.co" on every page
- [ ] Network tab: no external CSS/JS/font requests (apart from share links when clicked)
- [ ] Lighthouse (information only): accessibility ≥ 90, no console errors

## 6. Rollback

- Code: `git revert --no-edit pre-blogy..master` (a new commit, so no force-push), then `npm install` and `npm run build`.
- Each phase is its own commit, so you can revert only the phases after the last good one.
- No migrations are added; the DB backup from Phase 0 is only a precaution.

## 7. Not included (decided)

| Item | Why |
|---|---|
| Dark mode | Light only chosen (2026-11-04) |
| Theme demo photos | Not used (2026-11-04); local placeholders instead |
| Team, testimonials, sliders, video lightbox, date picker, counters | No content for them in a personal blog; their plugins aren't installed |
| Removing the Untree.co credit ($19 license) | Not chosen; the credit stays |
| Newsletter and comment forms | Built as Livewire components in the finish plan, using the theme's comment markup from `single.html` as the reference |

## 8. Bugs and questions found while implementing

| ID | Location | Problem / question | Decision |
|---|---|---|---|
| B01 | `blog/show.blade.php` (`@section('og_image', $post->featured_image)`, `@section('meta_description', $post->excerpt)`) | When the value is `null`, Blade treats the inline section as a block section and opens an output buffer that is never closed. Pest reported the test as "risky", and it showed up once `PostFactory` stopped setting `featured_image` (Phase 3) | Fixed in Phase 3: `og_image` uses the image or `default-og.png`; `meta_description` uses `?? ''`. Rule for all views: never pass a nullable value to an inline `@section` |
| B02 | Phase 3, step 4 | The sidebar / post-card / post-meta partials only make sense next to the pages that use them | Moved to Phase 4: they're created while converting the blog pages |
| B03 | Phase 3, step 2 | The plan named a view composer; header, footer and (Phase 4) sidebar need overlapping queries | `App\Support\SiteData` with `once()`-cached queries (one run per request), registered as view composers in `AppServiceProvider`. "Recent posts" is ordered by `published_at` (review-plan N01) |
| B04 | Local MySQL data | 34 of the 50 posts in `dev_blog` still have `placehold.co` URLs from the old factory. `Post::imageUrl()` passes absolute URLs through, so those pages still load images from placehold.co | Run `php artisan migrate:fresh --seed` (backup first) to reseed without them |
| B05 | `theme/_site-navbar.scss` | The dropdown arrows use the IcoMoon font (`\e313`, `\e315`), which isn't shipped (not copied, section 2), so they rendered as empty boxes | Phase 4: switched to the Bootstrap Icons font (`\f282` chevron-down, `\f285` chevron-right) |
| B06 | `resources/js/theme/init.js` (preloader) | The theme's `requestAnimationFrame` fade loop stalls when frames don't run (background tabs, headless Chrome): the page stayed behind a semi-transparent overlay | Phase 4: CSS transition + timer; the overlay ends with `display: none` (checked with a headless DOM dump) |
| B07 | List items, cards, thumbnails | The theme assumes pre-cropped demo photos. Other image sizes were squeezed, and list items overflowed on phones | Phase 4: `img-cover` classes (`object-fit` + `aspect-ratio`), fixed 140px list thumbnails, items stack under `sm`, and the logo column is `col-5 col-lg-2` with `text-nowrap` ("Dev Blog" wrapped in the theme's `col-2`) |
| B08 | Sidebar search form | The theme positions the search icon absolutely but never makes the form `position: relative`, so the icon was invisible or misplaced | Phase 4: `.search-form-wrap .sidebar-search-form { position: relative }` |
| B09 | Visual checks | Pages were checked with headless Chrome screenshots (desktop 1280px, phone 540px). Headless Chrome won't lay out narrower than about 500px, so a 390px capture is cropped, not the real phone layout | Use 540px for phone checks, or a real device / DevTools device mode |
