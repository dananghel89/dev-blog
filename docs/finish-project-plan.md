# Plan: finish the project (unfinished features)

Status: **Draft – not started**
Created: 2026-11-04

## 1. Order and prerequisites

This is the **sixth** plan. Start it only when all earlier plans are merged into `master`:

1. [blogy-theme-plan.md](blogy-theme-plan.md): the public site in the Blogy theme (Vite + Sass, Bootstrap 5.3 from npm), Livewire components for the post list, search and contact form, and the Untree.co footer credit.
2. [project-review-fix-plan.md](project-review-fix-plan.md): bug fixes, Pest test suite, starter kit (Fortify, Flux) removed, newsletter/legal pages failing cleanly.
3. [branding-assets-plan.md](branding-assets-plan.md) (done): identity in `config/project.php`, own favicon, default OG image.
4. [github-migration-plan.md](github-migration-plan.md): repo moved to GitHub (public), history rewritten.
5. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md): Laravel 13, Livewire 4.4, Vite 8, Pest 5, skeleton-aligned config.
6. **This plan:** builds the admin and the missing features directly on the upgraded stack.

Prerequisite check (Phase 0) before any work:
- `php artisan --version` → Laravel 13.x
- `composer show laravel/fortify livewire/flux` → not installed; `composer show livewire/livewire` → ≥ 4.4.8; `npm run build` → OK
- `php artisan test` → green

## 2. Decisions

| Topic | Decision |
|---|---|
| Stack | Laravel 13 · PHP 8.4 · **Filament 5** (`filament/filament:^5.11`, on the Livewire 4.4 already installed) · Pest 5 · public site in the **Blogy theme** built with Vite + Sass (Bootstrap 5.3 from npm); interactive public parts are **Livewire components** in `resources/views/livewire/` |
| Admin | **Filament** panel at `/admin` (decided 2026-11-04). **Single admin role**: every user who can log in is a full admin. No public registration. Users are created by the seeder or `php artisan make:filament-user`. Filament provides login, password reset, a profile page and **app-based MFA** (TOTP; it replaces the removed Fortify 2FA) |
| Post content | **Markdown** (Filament `MarkdownEditor`), rendered with `Str::markdown()` with raw HTML stripped and unsafe links disallowed. Code blocks are highlighted by **highlight.js from npm**, bundled by Vite (no CDN). No rich-text/HTML editor |
| Images | Featured images are uploaded to the `public` disk (`storage/app/public/posts/…`, served through `public/storage`). **Validation only, no resizing**: jpg/png/webp up to 2 MB, minimum 1200×630 |
| Newsletter | **Own, double opt-in** (decided 2026-11-04). Subscribers are stored in the DB. You send a published post to subscribers **manually**, once, with a Filament action. Mails are queued |
| Comments | **Moderated** (decided 2026-11-04). Name + email (never shown) + plain-text body. Every comment starts as *pending*; only *approved* ones are shown. No threads, no Gravatar, no Markdown |
| Spam protection | All public forms (contact, newsletter, comments): a hidden honeypot field plus a named rate limiter. A filled honeypot is stored as *spam* (comments, contact) or silently ignored (newsletter), and still shows the normal success message |
| Site settings | **Editable in Filament** (a *Site settings* page backed by a `settings` table). Defaults come from `config('project.*')` (branding plan), so nothing changes until edited |
| Legal pages | A small **Pages** resource (Markdown) for Privacy Policy and Terms. They are seeded with placeholder text that already covers the newsletter, comments, the contact form and the theme credit |
| Queue | `QUEUE_CONNECTION=database` (already set). Locally `composer dev` runs `queue:listen`. In production, the scheduler runs `queue:work --stop-when-empty` every minute, so a single cron line is enough on shared hosting |
| Migrations | New features get **new migrations** (`create_subscribers_table`, `add_newsletter_fields_to_posts_table`, …); existing migrations are not edited |
| Way of working | Test first for every feature (Pest; `Livewire::test()` for Filament pages; `Storage::fake('public')`, `Mail::fake()`, `Queue::fake()`), then the code, then a green suite. One commit per phase (with your approval) |

### Traceability (review-plan backlog → this plan)

| Backlog item (review plan, section 6) | Phase |
|---|---|
| Admin to write posts (Filament) | 1, 3 |
| Post format (HTML → Markdown, highlighting) | 2, 4 |
| Featured images (upload instead of `placehold.co`) | 2, 3, 4 |
| Newsletter (double opt-in) | 7 |
| Comments (moderated) | 8 |
| Privacy, Terms, sitemap, RSS | 5, 6, 10 |
| Contact messages stored, honeypot | 9 |
| About page hard-coded stats and tech stack | 6 |
| *New:* drafts can't be previewed before publishing | 3 |
| *New:* off-brand framework error pages | 12 |

---

## 3. Phases

### Phase 0 – Prerequisites

1. Run the prerequisite check (section 1).
2. Work directly on `master`: commit and push each phase only after you confirm it (I show the files and message first), with no feature branch.
3. Back up the MySQL DB: `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysqldump" -u root dev_blog > ../dev-blog-backup-YYYYMMDD.sql` (mysqldump isn't on the PATH; restore with `"/c/Wamp/bin/mysql/mysql8.3.0/bin/mysql" -u root dev_blog < ../dev-blog-backup-YYYYMMDD.sql`).

### Phase 1 – Filament panel and admin access

1. `composer require filament/filament:"^5.11" -W`, then `php artisan filament:install --panels` → `app/Providers/Filament/AdminPanelProvider.php` (id `admin`, path `admin`), registered in `bootstrap/providers.php`. Add `@php artisan filament:upgrade` to the `post-autoload-dump` script if the installer didn't.
2. **Panel configuration:**
   - `->login()`, `->passwordReset()`, `->profile()`
   - `->multiFactorAuthentication([AppAuthentication::make()->recoverable()])`, plus the migration the Filament docs give for the `app_authentication_secret` / `app_authentication_recovery_codes` columns on `users`
   - **no** `->registration()`
   - `->brandName(config('app.name'))`, `->favicon(asset('favicon.png'))`, `->colors(['primary' => Color::hex('#214252')])` (Blogy's primary), dark mode available in the admin
3. **Access:** `User implements FilamentUser` with `canAccessPanel(Panel $panel): bool` → `true` (single admin role; users only come from the seeder or the CLI). Without this, Filament returns 403 in production.
4. **Seeded admin:** `AdminUserSeeder` creates `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` (defaults `admin@example.com` / `password`, read through `config('project.seed')`), with `email_verified_at = now()`. Document both keys in `.env.example`; your real values go only in the local `.env`.
5. Password-reset mails use the existing mail settings (`MAIL_MAILER=log` locally).
6. `php artisan storage:link`. On Windows, if the symlink fails without admin rights, create a junction: `mklink /J public\storage storage\app\public`. `/public/storage` is already git-ignored.
7. Tests (`Admin/AccessTest`):
   - a guest at `/admin` → redirect to `/admin/login`
   - a logged-in user → 200
   - `/admin/register` → 404
   - `POST /register` → 404 (still, from review-plan R01)

### Phase 2 – Content model: Markdown and uploaded images

1. **Markdown rendering:** a `Post::contentHtml()` accessor:
   ```php
   Str::markdown($this->content, ['html_input' => 'strip', 'allow_unsafe_links' => false])
   ```
   It also adds `id`s to `h2`/`h3` (CommonMark heading-permalink extension), which can later drive a table of contents. The post view prints **only** this accessor unescaped. Remove the `{!! $post->content !!}`.
2. **Reading time and search** use the plain text (`strip_tags($this->content_html)`), so Markdown syntax isn't counted or matched. This fixes review-plan R13's remaining HTML-match issue.
3. **Featured image:** the column keeps its name; it now stores a **path on the `public` disk** (e.g. `posts/abc.webp`). `Post::imageUrl()` (theme plan) becomes: an uploaded file → `Storage::disk('public')->url()`; empty → the local placeholder in that ratio (cards) or the plain primary-colour cover (post page). When the image is replaced or the post is deleted, the old file is deleted (model `updating`/`deleting` events).
4. **Seeders:** `PostFactory` generates **Markdown** (headings, paragraphs, a fenced ```php block, a list) instead of HTML. Featured images are `null`, or one of a few local sample images that the seeder copies into the public disk (`database/seeders/images/*.webp`, made by you or generated).
5. Migration `add_comments_enabled_to_posts_table`: `boolean('comments_enabled')->default(true)` (used in Phase 8).
6. Tests:
   - `<script>` and `javascript:` links in Markdown are stripped
   - a fenced code block renders `<pre><code class="language-php">`
   - replacing or deleting a post deletes the old image (`Storage::fake`)

### Phase 3 – Filament resources: Posts, Categories, Tags

| Resource | Form | Table | Notes |
|---|---|---|---|
| **Posts** | `title` required, max 255 · `slug` required, unique (ignoring the current post), generated live from the title until edited by hand · `excerpt` nullable, max 255 (used as the meta description; the hint says so) · `category_id` select (searchable, can create a category inline) · `tags` multi-select relationship (can create inline) · `featured_image` `FileUpload` (`image`, `disk('public')`, `directory('posts')`, max 2048 KB, `imageResizeMode` off, min 1200×630 validation) · `content` `MarkdownEditor` required, max 100 000 · `is_published` toggle · `published_at` `DateTimePicker` (required when published; a future date = scheduled) · `comments_enabled` toggle | title (searchable), category, status badge (*Draft* / *Scheduled* / *Published*, computed from both fields), `published_at` (sortable, default sort desc), tag count. Filters: status, category, tag | Actions: **View on site** (published only), **Preview** (any post: a temporary signed URL `blog.preview`, valid 30 min, that renders the normal post view with a "Preview" banner), Delete. Bulk: publish now / unpublish / delete |
| **Categories** | `name` required, unique · `slug` (as for posts) · `description` nullable, max 500 · `color` `ColorPicker` (hex) | name with a colour swatch, published-post count, slug | Deleting a category leaves its posts uncategorised (`nullOnDelete`, existing FK). The confirmation modal says how many posts are affected |
| **Tags** | `name` required, unique · `slug` | name, published-post count | Deleting a tag detaches it (cascade on `post_tag`) |

1. The slug logic reuses `HasUniqueSlug` from the review plan (R15). The Filament form shows the final slug before saving.
2. **Preview route:** `GET /blog/preview/{post}` with the `signed` middleware. It renders `blog.show` for any post status, with `noindex` and a banner.
3. Tests per resource:
   - list renders
   - create (valid + validation errors + unique slug)
   - edit
   - delete
   - Posts only: the image upload, the status-badge logic, the preview URL (valid signature → 200, tampered → 403)

### Phase 4 – Public rendering

1. The post page:
   - prints `content_html`
   - loads **highlight.js** from npm only on post pages: a separate Vite entry `resources/js/post.js` imports `highlight.js/lib/core` with a few languages (php, sql, js, css, html/xml, bash, json) and one light theme that matches Blogy, added with `@vite('resources/js/post.js')` in the post view
   - styles for tables, images and inline code in `.post-content-body` (`resources/scss/app/`)
2. `imageUrl()` is used everywhere: cards, the post cover, `og:image` (absolute URL, falling back to `default-og.png`).
3. `<link rel="canonical">` on every page; `<meta name="robots" content="noindex">` on search results and previews.
4. Tests:
   - a post with an uploaded image shows the `/storage/posts/…` URL; one without shows the placeholder in cards and no image on the post page
   - `og:image` is absolute
   - the `post.js` bundle is referenced on post pages only

### Phase 5 – Pages (Privacy Policy, Terms)

1. Migration `create_pages_table`: `title`, `slug` unique, `content` (Markdown, longText), `is_published`, timestamps. Model `Page` with `HasUniqueSlug` and the same Markdown accessor as posts (move it to a shared trait `RendersMarkdown`).
2. Filament **Pages** resource:
   - `title`, `slug`, `content` (`MarkdownEditor`), `is_published`
   - reserved slugs are rejected: `admin`, `blog`, `about`, `contact`, `newsletter`, `feed`, `sitemap`, `up`, `storage`, `livewire`, `filament`
3. Route: `GET /{page:slug}`, registered **last** in `routes/web.php` with `->where('page', '[a-z0-9-]+')`. A draft or unknown page → 404. Check that `/up`, `/admin`, `/storage/…` and the Livewire endpoint aren't swallowed.
4. The footer links to these pages come in Phase 6, where the *Legal pages* settings choose them.
5. `PageSeeder` creates "Privacy Policy" (`privacy-policy`) and "Terms of Use" (`terms-of-use`) with placeholder Markdown. The privacy text names, as headings with placeholder paragraphs for you to complete:
   - third parties: none for assets (CSS, JS and fonts are self-hosted); the share links only contact X/LinkedIn/Facebook when clicked
   - the data stored: contact messages, newsletter subscribers (email, confirmation date), comments (name, email, text)
   - `localStorage` for the theme
   - the session cookie
6. Tests:
   - a published page renders with its title and content; a draft → 404
   - a reserved slug is rejected

### Phase 6 – Site settings and the About page

1. Migration `create_settings_table`: `key` unique, `value` (json), timestamps. `App\Support\Settings` is a singleton that loads every row **once per request** (cached with `Cache::rememberForever('project.settings')`, cleared on save), merged over `config('project.*')`. The `settings('key')` helper is registered via `autoload.files`. A `QueryException` (fresh DB, table missing) falls back to config, so artisan commands and the first migration never break.
2. Filament **Site settings** page (a custom page with a form, in its own navigation group), with these sections:
   - *General*: tagline, author name, contact email
   - *Social links*: GitHub, LinkedIn, X, Mastodon, …; empty means hidden
   - *Home*: hero heading, hero text
   - *About*: bio (Markdown), career start year, tech stack (a repeater of group + items), avatar (`FileUpload`, square, min 300×300)
   - *Legal pages*: privacy page, terms page (selects of published pages)
3. Views read `settings()` instead of `config('project.*')`. `APP_NAME` stays in `.env` (it is also used by mail and the session cookie name).
4. **Footer legal links:** the footer links to the privacy and terms pages chosen in *Legal pages*, and hides a link that isn't set. This replaces the link removed in review-plan R02. `PageSeeder` sets both settings.
5. **About stats become real:**
   - `years_experience` = the current year − the career start year
   - `posts_written` = the published post count
   - `technologies` = the count of tech-stack items
   - `countries_reached` is removed: there's no data source, and analytics aren't in scope
   - `AboutController` loses its hard-coded arrays
6. Tests:
   - defaults render the branding-plan config values
   - footer legal links appear when the settings are set, and are hidden otherwise
   - edited settings appear on the site immediately (cache cleared)
   - the About stats match the DB
   - a missing settings table doesn't throw

### Phase 7 – Newsletter (double opt-in)

1. Migration `create_subscribers_table`: `email` unique, `token` (64 chars, unique), `confirmed_at` nullable, `unsubscribed_at` nullable, timestamps. No IP or user-agent is stored. Migration `add_newsletter_sent_at_to_posts_table`.
2. **Subscribe:** the Livewire component `newsletter-form` (theme markup; used in a home-page section and in the footer) with a `subscribe()` action: `RateLimiter::attempt('newsletter:'.ip, 3, …, 600)`, plus a honeypot. No POST route.
   - The response is **always the same** ("Check your inbox to confirm"), so it can't be used to find out who is subscribed.
   - New email → create the subscriber and queue `ConfirmSubscription`.
   - Existing, unconfirmed → re-send the confirmation at most once per hour.
   - Confirmed → do nothing.
   - Unsubscribed → reset it to unconfirmed and send a new confirmation.
3. **Confirm:** `GET /newsletter/confirm/{subscriber:token}` → sets `confirmed_at`, then shows a themed "You're subscribed" page. An unknown token → 404 (not a fake success, as before R11).
4. **Unsubscribe:**
   - Every newsletter mail has a link to `GET /newsletter/unsubscribe/{subscriber:token}`, which shows a page with a "Confirm unsubscribe" button (a `POST`). The `POST` step means mail scanners that prefetch links can't unsubscribe anyone.
   - The mails also carry `List-Unsubscribe` and `List-Unsubscribe-Post: List-Unsubscribe=One-Click` headers (RFC 8058); one-click `POST` requests unsubscribe directly.
   - Unsubscribing sets `unsubscribed_at` and keeps the row, so a later re-subscribe works.
5. **Home page and footer:** `<livewire:newsletter-form />` in the theme style, with inline validation errors, a loading state and the success message. None of the old fake JS.
6. **Sending:**
   - Posts table: a **Send to subscribers** action, visible when the post is published and `newsletter_sent_at` is null.
   - Its confirmation modal shows the number of confirmed subscribers.
   - It dispatches one queued `NewPostNewsletter` mail per confirmed subscriber (chunked by 100), then sets `newsletter_sent_at`. The action is then hidden, so the post can't be sent twice.
   - The mail contains the title, excerpt, featured image, a "Read the post" link and the unsubscribe link, in the same visual style as the contact mail.
7. **Admin:** a Filament **Subscribers** resource:
   - list with a status badge (*Pending* / *Confirmed* / *Unsubscribed*), filter, search; delete
   - a dashboard counter
8. **Scheduler (production):** in `routes/console.php`:
   ```php
   Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
   ```
   The README gets the single cron line (`* * * * * php /path/artisan schedule:run`).
9. Tests:
   - subscribe via `Livewire::test('newsletter-form')` (new, duplicate, confirmed, unsubscribed → re-subscribe), the same response every time, honeypot, rate limit
   - confirm: valid token, unknown token → 404
   - unsubscribe: the GET page; the POST; one-click headers present
   - send: the action queues one mail per confirmed subscriber only; `newsletter_sent_at` is set; the action is hidden after sending

### Phase 8 – Comments (moderated)

1. Migration `create_comments_table`:
   - `post_id` (FK, cascade on delete)
   - `name` (max 80), `email` (max 255), `body` (text, max 3000)
   - `status` (`pending` / `approved` / `spam`, default `pending`), `approved_at` nullable
   - timestamps; index on `(post_id, status)`

   Model `Comment` with `scopeApproved()`. `Post::comments()` / `approvedComments()`.
2. **Public form:** the Livewire component `blog.comments` (props: `post`) on the post page. It uses the theme's `comment-wrap` / `comment-list` / `comment-form-wrap` markup from `single.html` as the reference, without replies:
   - shown only for published posts with `comments_enabled`
   - fields: name, email (with a note that it isn't published), comment, plus a honeypot
   - a `submit()` action with `RateLimiter::attempt('comments:'.ip, 5, …, 600)`; no POST route
   - after submitting: "Thanks, your comment is awaiting moderation"
   - a filled honeypot → saved as `spam` with the same message
   - the name and email are not remembered (no cookie)
3. **Display:** approved comments, oldest first, with the count in the section heading. The body is rendered with `nl2br(e())`, and the date is relative ("3 days ago"). Post cards may show the approved count (`withCount`).
4. **Notification:** a new non-spam comment queues a mail to `settings('contact_email')` with the post title, the comment and a link to the Filament edit screen.
5. **Admin:** a Filament **Comments** resource:
   - table with the post (linked), name, an excerpt of the body, a status badge and the date; filter by status/post
   - actions: **Approve**, **Mark as spam**, **Delete**, plus bulk approve / bulk spam
   - a navigation badge with the number of pending comments
   - the edit form allows fixing typos in the body; email and name are read-only
   - the Posts form gets a "Comments" relation manager
6. Tests:
   - `Livewire::test('blog.comments', ['post' => …])`: submitting creates a pending comment (not shown until approved); approved comments appear; the body is escaped
   - honeypot → spam, no mail; rate limit
   - no form on a post with `comments_enabled = false` or a draft
   - approve/spam actions; the pending badge count

### Phase 9 – Contact messages stored, honeypot

1. Migration `create_contact_messages_table`: `name`, `email`, `subject`, `message`, `status` (`inbox` / `spam`), `read_at` nullable, timestamps.
2. The `contact-form` Livewire component (theme plan): save the message, then queue `ContactFormMail` (recipient `settings('contact_email')`). A honeypot → saved as `spam`, no mail, the normal success message.
3. Filament **Messages** resource:
   - read-only view (opening it sets `read_at`); actions mark as spam / not spam, delete
   - a filter (All / Inbox / Spam)
   - a navigation badge with the unread inbox count
   - a "Reply" action that opens `mailto:` with the subject
4. Tests: stored + mailed; honeypot → spam without mail; viewing marks it as read; the filters.

### Phase 10 – Feeds and SEO

1. **Sitemap:** `GET /sitemap.xml`, a Blade XML view listing home, `/blog`, `/about`, `/contact`, published posts (`lastmod` = `updated_at`), categories and tags with published posts, and published pages. Served as `application/xml` and cached for 1 hour (cleared when a post or page is saved).
2. **RSS:** `GET /feed.xml` (RSS 2.0): the 20 latest published posts with title, link, guid, `pubDate`, the excerpt as the description and the category. Add `<link rel="alternate" type="application/rss+xml">` to the layout and an RSS icon in the footer.
3. `public/robots.txt`: add `Sitemap: {APP_URL}/sitemap.xml`. Since `robots.txt` is static, generate the line from a route, or document setting it at deploy time. Disallow `/admin` and `/blog/preview`.
4. Tests:
   - the sitemap contains a published post and excludes drafts and scheduled posts
   - the feed is valid XML with 20 items max
   - the content types are right

### Phase 11 – Dashboard

1. A Filament *StatsOverview* widget with these counters:
   - published posts
   - drafts + scheduled posts
   - pending comments
   - confirmed subscribers
   - unread messages
2. A "Latest comments" table widget (the 5 newest pending comments, with approve/spam actions) and a "Recent messages" widget (the 5 newest inbox messages).
3. Tests: the widgets render with the expected counts.

### Phase 12 – Polish

1. **Error pages:**
   - `404.blade.php` is already themed (theme plan). `419.blade.php` uses the public layout in the same way.
   - `500.blade.php` and `503.blade.php` are **standalone**: no `settings()`, no DB access, inline styles. They must still render when the database is down.
   - Filament keeps its own error handling.
2. **Accessibility:**
   - `alt` text on featured images (the post title)
   - `aria-label` on the icon-only buttons (search toggle, theme toggle, share links, social icons)
   - visible focus styles in both themes
3. **Mail:** the contact, comment-notification, confirmation and newsletter mails share one Blade mail layout (extracted from the current contact template).
4. **Cleanup:** remove any remaining unused CSS in `public/css/styles.css` and in the `@push('styles')` blocks, and check `pint --test`.

### Phase 13 – Verification and documentation

1. `php artisan migrate:fresh --seed` on MySQL (local; backup from Phase 0) → the seeded admin can log in; the demo blog is complete, with Markdown posts, categories, tags, a few approved and pending comments, subscribers and the legal pages.
2. `php artisan test` → green; `vendor/bin/pint --test` → clean.
3. Smoke checklist (section 4).
4. Docs:
   - **`CLAUDE.md`:** the Filament panel and resources, the Markdown rendering rule (only `content_html` is unescaped), `settings()` and its defaults, the queue/scheduler, the honeypot + rate-limiter pattern, the newsletter flow
   - **`README.md`:** the feature list, the admin login (`SEED_ADMIN_*`), the cron line, credits (Filament added)
   - Mark the review plan's backlog items as done (pointing to this plan).
5. Final review of the plan's commits on `master`, with your approval.

## 4. Smoke checklist (final)

Public site:
- [ ] Home: hero texts from Settings; latest posts with images or placeholders; category links; newsletter form (subscribe → confirmation mail in the log → confirm link → success page)
- [ ] Post: Markdown with highlighted code, tags, related posts, share links; the comment form → pending → approved in the admin → visible
- [ ] A draft, a scheduled or an unknown post → themed 404; the preview link from the admin works, and a tampered one → 403
- [ ] Search, category and tag pages; the About page with real stats; Contact (stored, mailed, honeypot, rate limit)
- [ ] Footer: Privacy Policy / Terms of Use pages; social links from Settings; RSS link
- [ ] `/sitemap.xml`, `/feed.xml`, `/robots.txt`

Admin (`/admin`):
- [ ] Login, password reset, profile, MFA setup and login with a TOTP code
- [ ] Posts: create → upload an image → schedule → preview → publish; edit; delete (the image file is removed); Send to subscribers (once only)
- [ ] Categories and Tags CRUD; deleting a category leaves its posts uncategorised
- [ ] Comments: approve, spam, delete, bulk actions, the pending badge
- [ ] Messages: view (marked as read), spam/not spam, delete, the unread badge
- [ ] Subscribers list and filters; Pages CRUD with the reserved-slug check; Site settings save and show on the site
- [ ] Dashboard counters and widgets

CLI:
- [ ] `php artisan test`, `pint --test`, `php artisan route:list`, `php artisan schedule:list` (queue worker entry)

## 5. Not included (decided)

| Item | Why |
|---|---|
| Roles, permissions, a users screen | Single admin role chosen; users come from the seeder or `make:filament-user` |
| WYSIWYG / rich-text editor | Markdown chosen |
| Image resizing / thumbnails | Validation only; can be added later with intervention/image or Filament's image editor |
| Threaded replies, comment voting, Gravatar | Simple moderated comments chosen; Gravatar would send visitors' email hashes to a third party |
| External newsletter service (Mailchimp, …) | Own double-opt-in newsletter chosen |
| Automatic sending on publish | Manual "Send to subscribers" chosen, so scheduled or edited posts never mail by accident |
| Filament custom theme (Tailwind build) | The default Filament theme with the brand colour is enough; a custom theme would need a separate Tailwind build next to the Bootstrap/Sass one |
| Dark mode on the public site | Light only (Blogy theme plan, 2026-11-04) |
| Theme demo photos | Not used (2026-11-04) |
| Analytics | Not requested; the "countries reached" stat is removed instead |
| Multi-language | Single language |

## 6. Bugs and questions found while implementing

| ID | Location | Problem / question | Decision |
|---|---|---|---|
| – | – | – | – |
