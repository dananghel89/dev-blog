# Plan: branding and third-party assets

Status: **Draft – not started**
Created: 2026-11-04 · Revised: 2026-11-04 (Blogy theme plan added as priority 1: assets now come from npm via Vite; placeholders moved to the theme plan)

> This is a technical checklist based on the published license texts, not legal advice.

This plan takes the place of agency-cms's theme-licensing plan. The theme license itself (Blogy by Untree.co, CC BY 3.0, footer credit, no redistribution) is handled in [blogy-theme-plan.md](blogy-theme-plan.md), section 2. What's left here:
- check the notices of the npm libraries
- replace the assets that aren't yours (the Laravel favicon)
- fill in the placeholder identity ("Your Name", `href="#"` links, fake handles)
- add the missing default OG image

## 1. Order

1. [blogy-theme-plan.md](blogy-theme-plan.md)
2. [project-review-fix-plan.md](project-review-fix-plan.md)
3. **This plan**
4. [github-migration-plan.md](github-migration-plan.md)
5. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md)
6. [finish-project-plan.md](finish-project-plan.md)

## 2. Facts (checked 2026-11-04; state after the theme plan)

| Asset | Where | License / owner | Notes |
|---|---|---|---|
| Blogy theme (SCSS partials, navbar script) | `resources/scss/theme/`, `resources/js/theme/` | CC BY 3.0, © Untree.co | Footer credit + `/*! */` banners + README credit (theme plan, section 2) |
| Bootstrap 5.3 (SCSS + JS) | npm, bundled by Vite | MIT, © The Bootstrap Authors | The `/*! Bootstrap … MIT */` banner survives the build (`esbuild.legalComments: 'inline'` in `vite.config.js`) |
| Bootstrap Icons | npm, bundled by Vite (font files emitted to `public/build`) | MIT | Banner in the bundled CSS |
| Work Sans | npm `@fontsource/work-sans`, bundled | SIL OFL 1.1 | The font files are redistributed in the build; OFL allows it. The license stays in `node_modules`, and the README credits the font |
| AOS | npm, bundled | MIT | |
| `public/favicon.ico`, `favicon.svg`, `apple-touch-icon.png` | `public/` | **Laravel logo** | Comes with the Laravel skeleton. Not your artwork, so it should be replaced. The theme's `favicon.png` is **not** used (theme plan) |
| Placeholder images | `public/images/placeholders/*.svg` | Your own (made in the theme plan, Phase 3) | They replace `placehold.co` and the theme's demo photos |
| Default OG image | `layouts/app.blade.php` → `images/default-og.png` | – | **The file doesn't exist**, so shares without a featured image show no picture |
| Laravel framework, Livewire, Filament (finish plan) | `vendor/` | MIT | No notice to add: the license stays inside `vendor/` |

**No CDNs** after the theme plan: every CSS, JS and font file is served from your own domain. The privacy policy (finish plan) doesn't need to list any asset CDN.

### Placeholder identity

The theme plan ports these into the new Blogy partials as they are. This plan replaces them:

| Where (after the theme plan) | Placeholder |
|---|---|
| `.env` `APP_NAME=Laravel` | Every `<title>` ends in "- Laravel" |
| layout `<meta name="author">` | "Your Name" |
| home, About | "Hi, I'm **Your Name**" / the about blurb |
| footer *Social*, contact info | Three fake social handles built on the old site name, which **may be real people's accounts**. **Replaced with plain network names on 2026-11-04**; the links still go to `#` |
| header logo, footer copyright, `emails/contact-form.blade.php` | The old site name hard-coded in markup. **Done 2026-11-04**: the project is now **Dev Blog**; the logo is `Dev Blog.` and the rest uses `config('app.name')` |
| `composer.json` `name`/`description` | Changed to `dananghel/dev-blog` in the review plan |

## 3. Required vs optional

| Item | Required? | Decision |
|---|---|---|
| Untree.co footer credit, CC BY 3.0 attribution | **Yes** (their license) | Done in the theme plan; must never be removed |
| Keep the MIT notices of Bootstrap / Icons / AOS | **Yes** (MIT condition) | Met by the `/*!` banners, which survive the Vite build thanks to `esbuild.legalComments: 'inline'` (theme plan, Phase 2). Check in Phase 5 |
| OFL notice for Work Sans | Yes, when redistributing the font files | README credit + the license in the npm package |
| Credit the libraries in the README | No (nice to have) | README "Credits": Blogy/Untree.co (required), Bootstrap, Bootstrap Icons, Work Sans, AOS |
| Replace the Laravel favicon | No, but it's Laravel's logo, not your brand | **Replace** (Phase 2) |
| Project license | No | As in agency-cms: no `LICENSE` file; `composer.json` `"license": "proprietary"`. Your code is "all rights reserved"; the theme and libraries keep their own licenses |
| Fake social handles | – | **Removed** unless you give the real URL (Phase 1) |

## 4. Phases

### Phase 1 – Identity in one place

1. Extend `config/project.php` (created by review-plan R10). Every value comes from `.env` and has a **neutral default**, so the public repo contains no personal values:
   ```php
   'name'          => env('APP_NAME', 'Dev Blog'),
   'tagline'       => env('BLOG_TAGLINE', 'Full stack development blog'),
   'author'        => env('BLOG_AUTHOR', 'Blog Author'),
   'contact_email' => env('CONTACT_EMAIL', 'hello@example.com'),
   'social' => [
       'github'   => env('SOCIAL_GITHUB'),
       'linkedin' => env('SOCIAL_LINKEDIN'),
       'x'        => env('SOCIAL_X'),
       // only networks with a URL are rendered
   ],
   ```
2. `.env`: `APP_NAME="Dev Blog"` (already set on 2026-11-04 in `.env` and `.env.example`), plus your real `BLOG_AUTHOR`, `CONTACT_EMAIL` and social URLs (local only). `.env.example`: the keys with empty or neutral values.
3. Views:
   - The header logo, `<title>`, meta author, the home/About texts, the footer *About* blurb and copyright, and the contact info read `config('project.*')`.
   - `partials/social-links.blade.php` loops over `config('project.social')` with Bootstrap Icons and **renders only links that are set**. It's used in the footer, About and Contact.
   - The fake handles are removed.
4. The logo keeps the theme's style, the name followed by a primary-coloured dot: `Dev Blog<span class="text-primary">.</span>` (like the theme's `Blogy.`; decided 2026-11-04). It's built from `config('project.name')`.
5. Contact mail template: the name already uses `config('app.name')` (2026-11-04). Its colours are taken from the theme (`#214252` instead of the old indigo).
6. Tests: `BrandingTest`:
   - the configured name and author appear on the home page
   - an unset social network renders no link
   - no `href="#"` remains in the header/footer
   - the Untree.co credit is still present (it's also covered by `ThemeCreditTest`)
7. The finish plan later moves these values into an editable **Site settings** page in Filament, with these config values as the defaults.

### Phase 2 – Favicon

1. A new SVG favicon: an "A." monogram in the theme's primary `#214252` with a white dot, matching the logo style.
2. Export `favicon.ico` (32×32 and 16×16) and `apple-touch-icon.png` (180×180) from the SVG. Replace the three Laravel files.
3. The layout references `favicon.svg` (`type="image/svg+xml"`), `favicon.ico` and the touch icon.
4. Check the tab icon in Chrome and Firefox, in light and dark OS mode.

### Phase 3 – Placeholders (moved)

Done in [blogy-theme-plan.md](blogy-theme-plan.md), Phase 3: local SVGs in three ratios, `Post::imageUrl()`, and `PostFactory` without `placehold.co`. Here, only:
1. About: show your photo if `public/images/avatar.jpg` exists; otherwise `avatar.svg`.
2. Check: `grep -rn "placehold.co" app database resources` → nothing.

### Phase 4 – Default Open Graph image

1. Create `public/images/default-og.png` (1200×630): the theme primary background, the brand name in Work Sans and the tagline. Generate it once with a small PHP GD script run from outside the repo (GD is enabled in PHP 8.4), or design it yourself.
2. Layout: `og:image` falls back to `default-og.png` when a post has no featured image. Today an empty `featured_image` yields `content=""`. Also add `twitter:image`, and absolute URLs (`asset()` is absolute once `APP_URL` is right, see review-plan R12).
3. Check with a local OG preview (view source) and, after deployment, the LinkedIn Post Inspector.

### Phase 5 – Credits check and wrap up

1. `npm run build`, then check that the compiled CSS/JS in `public/build/assets/` still contains the `/*!` banners: Blogy/Untree.co, Bootstrap and Bootstrap Icons.
2. README "Credits": the Blogy line from the theme plan, then Bootstrap, Bootstrap Icons, Work Sans (OFL 1.1), AOS.
3. `composer.json`: `"license": "proprietary"`.
4. `CLAUDE.md`: identity values live in `config/project.php` / `.env` (neutral defaults, real values only in the local `.env`).
5. `php artisan test` → green; `vendor/bin/pint --test` → clean; a browser check of home, about, contact, a post with and without an image, and the footer.

## 5. Final state

| Phase | Commit | Result |
|---|---|---|
| Identity | – | `config/project.php` + `.env`; no "Your Name", no fake handles, no `#` links |
| Favicon | – | Own favicon (SVG, ICO, touch icon) |
| Placeholders | – | Done in the theme plan; About avatar |
| OG image | – | `default-og.png`, with a working fallback |
| Credits | – | Banners checked in the build, README credits, `"license": "proprietary"` |

## 6. Sources

- Untree.co license: https://untree.co/license/ · CC BY 3.0: https://creativecommons.org/licenses/by/3.0/
- Bootstrap license: https://github.com/twbs/bootstrap/blob/main/LICENSE
- Bootstrap Icons license: https://github.com/twbs/icons/blob/main/LICENSE
- Work Sans: https://fontsource.org/fonts/work-sans (OFL 1.1)
- AOS: https://github.com/michalsnik/aos (MIT)
