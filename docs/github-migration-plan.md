# Plan: move the repository from Bitbucket to GitHub

Status: **Draft – not started**
Created: 2026-11-04
Model: the agency-cms migration (2026-10-31 → 2026-11-02), done the same way: rewrite the history to the GitHub noreply address with one-line messages, push to GitHub, review it as a visitor, make it public, then delete Bitbucket.

## 1. Order and decisions

Plan order in `docs/`:

1. [blogy-theme-plan.md](blogy-theme-plan.md)
2. [project-review-fix-plan.md](project-review-fix-plan.md)
3. [branding-assets-plan.md](branding-assets-plan.md)
4. **This plan**
5. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md)
6. [finish-project-plan.md](finish-project-plan.md)

| Topic | Decision |
|---|---|
| When | **After** the theme, review/fix and branding plans are merged into `master`, **before** the Laravel 13 upgrade |
| Theme license | The repo is public with the Blogy theme integrated (decided 2026-11-04). It complies with the Untree.co license as described in [blogy-theme-plan.md](blogy-theme-plan.md), section 2: the footer credit is on every page, and only the adapted code is in the repo, never the template package (demo HTML, images, fonts, bundled vendor files). 4.8 checks this as a visitor |
| From | `https://dananghel89@bitbucket.org/dan-anghel/dev-blog.git` (current `origin`, branch `master`) |
| To | `https://github.com/dananghel89/dev-blog.git` (HTTPS, like agency-cms) |
| Visibility | **Public** (decided 2026-11-04), but only after you review it as a visitor. It is created **private** first |
| Commit identity | Every commit (author **and** committer): `Dan Anghel <7040327+dananghel89@users.noreply.github.com>`. Your gmail address is never used (your per-host email rule) |
| Commit messages | One-line `type: description`. The original 2 commits are renamed (section 3); commits from the review and branding plans are already written in this style |
| History scrub | Identity, messages **and the old brand in file contents** (decided 2026-11-04: the project is now called **Dev Blog**, and the old site name, its domain, its contact address and the fake social handles built on it must not stay in the public history). See 1.1 and 4.3 |
| History purge | None. The `example/` folder removed in `c36158d` is Laravel starter-kit code (MIT), not personal data |
| Bitbucket | **Deleted** after GitHub is verified (you do this in the browser). Until then it is the fallback |
| Tools | Standard git only (`git filter-branch`, `git bundle`). `gh` and `git filter-repo` aren't needed. GitHub steps happen in the browser |

### 1.1 What the history contains today (checked 2026-11-04)

- 2 commits on `master` (`4b496c5` Upload project files to repository, `c36158d` Remove example directory), both authored and committed with your **gmail** address. The review and branding plans add more commits with your Bitbucket address (the local identity while the remote is Bitbucket).
- No `.env` was ever committed (`git log --all -- .env` → nothing). The local database is MySQL (`dev_blog`, since 2026-11-04) and lives outside the repo; the old `database/database.sqlite` was git-ignored (`*.sqlite*`), so no database was ever committed. There are no API keys or tokens.
- Your gmail address does **not** appear in any file content, in any commit.
- Your own name and account stay as planned (decided 2026-11-04): the commit author name, the `dananghel89` GitHub account in the repo URL and noreply address, and the composer package `dananghel/dev-blog`.
- File contents with the **old brand**, all removed from the current files on 2026-11-04, when the project was renamed **Dev Blog**:
  - the old site name and its domain (header, footer, layout title, mail template, placeholder image text)
  - the old contact address (`ContactController`, `ContactFormMail`, contact page, footer)
  - three fake social handles built on the old name (footer, contact page)
  - their mentions in older versions of `docs/`

  **Decision: scrubbed from every commit** (4.3). The values are deliberately not written in this plan: 4.3 reads them from the first commit.
- No passwords. The only `password` hits are starter-kit Livewire properties in `example/` (`public string $password = ''`).
- Size: about 170 KiB packed.
- `.gitattributes` has `* text=auto eol=lf` and your global `core.autocrlf=true`. The rewrite runs with `core.autocrlf=false`, so line endings aren't touched.

## 2. Prerequisites (already written into the earlier plans)

1. **Commit messages** during the theme, review and branding plans are one-line `type: description`.
2. **Neutral defaults** for personal values: `CONTACT_EMAIL`, `BLOG_AUTHOR`, and the social URLs are read from `.env` with `example.com` / empty defaults (review-plan R10, branding-plan Phase 1). `APP_URL=http://dev-blog.test` in `.env.example` is a local-only hostname, kept on purpose (review-plan R12). Your real values exist only in your local `.env`, which is never committed.

## 3. New commit messages

| Original | New |
|---|---|
| `Upload project files to repository` | `feat: add developer blog on the Laravel Livewire starter kit` |
| `Remove example directory` | `chore: remove starter-kit example views` |
| Review/branding-plan commits | unchanged (written in style) |

## 4. Steps

### 4.1 Pre-flight (local)

1. The review and branding plans are finished on `master`. `git status` → clean; `git branch` → only `master`.
2. `git fetch origin` and `git status` → `master` is in sync with `origin/master` (Bitbucket), so the fallback has everything.
3. `php artisan test` → green. This is the reference for after the rewrite.
4. **Backup** outside the repo: `git bundle create ../dev-blog-pre-github-YYYYMMDD.bundle --all`, then `git bundle verify ../dev-blog-pre-github-YYYYMMDD.bundle`.
5. Record the "before" state for comparison:
   - `git rev-list master > ../dev-blog-commits-before.txt`
   - `git rev-list --count master`

### 4.2 ⏸ You: create the empty GitHub repo (browser)

1. Go to github.com → **New repository**. Owner `dananghel89`, name **`dev-blog`**, **Private**.
   **Do not** add a README, .gitignore or license; it must be empty.
2. Settings → General → Features: turn off **Issues, Wiki, Projects, Discussions**, as in agency-cms.
3. Account settings → Emails: "Keep my email addresses private" stays **on**. "Block command line pushes that expose my email" can stay **on**, because every rewritten commit uses the noreply address. If a push is rejected for email privacy, a commit still has a non-noreply address, so go back to 4.4.
4. Tell me when it's created.

### 4.3 Rewrite the history (local)

Run in Git Bash from the repo root. It rewrites `master` only and keeps the original author/commit dates.
- The env filter sets the identity, and the message filter renames the two original commits.
- The **tree filter** removes the old brand from every commit (decided 2026-11-04).

The old values are **never written in the repo**: the commands read them from the first commit.

```bash
export FILTER_BRANCH_SQUELCH_WARNING=1
# the old contact address, read from the first commit; the old site name and domain come from it
export OLD_EMAIL=$(git show 4b496c5:app/Mail/ContactFormMail.php | grep -oP "to: '\K[^']+")
export OLD_DOMAIN=${OLD_EMAIL#*@}
export NAME=${OLD_DOMAIN%%.*} TLD=${OLD_DOMAIN#*.}
echo "$OLD_EMAIL / $NAME / $TLD"   # check all three are filled before running

# the tree filter lives outside the repo, so its quoting stays simple
cat > ../scrub-dev-blog.sh <<'EOF'
grep -rlIZ -i --exclude-dir=vendor --exclude-dir=node_modules -e "$NAME" . 2>/dev/null \
| xargs -0 -r sed -i -E \
    -e "s/[A-Za-z0-9._%+-]+@$NAME[\\]?[.]$TLD/hello@example.com/gI" \
    -e "s/text=$NAME[\\]?[.]$TLD/text=dev-blog/gI" \
    -e "s/$NAME-site/dev-blog/gI" \
    -e "s#github[.]com/$NAME\\b#github.com/example#gI" \
    -e "s/@${NAME}_dev/@example/gI" \
    -e "s#linkedin[.]com/in/$NAME\\b#linkedin.com/in/example#gI" \
    -e "s#(<span[^>]*>|[*][*])?$NAME(</?span[^>]*>|[*][*])*[\\]?[.]?(</?span[^>]*>|[*][*])*$TLD#Dev Blog#gI"
EOF
export SCRUB="$(pwd)/../scrub-dev-blog.sh"

git -c core.autocrlf=false filter-branch --force \
  --env-filter '
    export GIT_AUTHOR_NAME="Dan Anghel"
    export GIT_AUTHOR_EMAIL="7040327+dananghel89@users.noreply.github.com"
    export GIT_COMMITTER_NAME="Dan Anghel"
    export GIT_COMMITTER_EMAIL="7040327+dananghel89@users.noreply.github.com"' \
  --msg-filter '
    sed -e "1s/^Upload project files to repository$/feat: add developer blog on the Laravel Livewire starter kit/" \
        -e "1s/^Remove example directory$/chore: remove starter-kit example views/"' \
  --tree-filter 'sh "$SCRUB"' \
  -- master
```

- The rules, in order:
  - the old contact address → `hello@example.com`
  - the old placeholder image text and the old `package-lock.json` project name → `dev-blog`
  - the three fake social handles → `example`
  - every form of the old site name: plain, `<span>`-wrapped, or with Markdown `**` around it or around the dot → `Dev Blog`
- Your identity is **not** touched: the author name, the `dananghel89` GitHub account and the Bitbucket user. None of them is followed by the old domain ending, so no rule matches them.
- The rules use bracket expressions (`[.]`, `[*]`, `[\]`) on purpose. With backslash escapes inside double quotes, the "optional backslash" turned into a literal `\?`, and the dry run on 2026-11-04 matched nothing.
- **Dry run first:** clone the repo into a scratch folder (`git clone . ../dev-blog-dryrun`), run the same commands there, and run check 4.4.5. For any form of the old name the rules missed, add a rule, then run it for real.
- The original history stays reachable at `refs/original/refs/heads/master` until 4.7, as well as in the bundle and on Bitbucket.

### 4.4 Verify the rewrite (local, before any push)

All of these must pass; if one fails, reset to the backup (section 6) and fix the command.

1. **Same number of commits:** `git rev-list --count master` equals the "before" count.
2. **Identity:** `git log --format='%an <%ae> | %cn <%ce>' master | sort -u` → exactly one line, with the noreply address for both author and committer.
3. **Messages:** `git log --format=%s master` shows the new first-line messages and no other changes.
4. **No gmail anywhere:** `git log --format='%ae %ce' master | grep -c gmail` → `0`.
5. **Old brand gone:** `git grep -n -i -E "$NAME" $(git rev-list master) | grep -v -i -E "dan[ -]?$NAME"` → nothing. The second grep keeps your identity (author name, `dananghel89`, `dan-anghel`), which is allowed.
6. **Only the intended content changed:** for every old/new pair (`paste ../dev-blog-commits-before.txt <(git rev-list master)`), `git diff --stat <old> <new>` lists only files that contained the old brand: views, controller, Mailable, `docs/*.md` and the theme banners. Any other file means line endings changed, so stop.
7. **Dates kept:** `git log --format='%ad %s' master` shows the original dates (2026-02-26, 2026-03-01, …).
8. **App still works:** `composer dump-autoload`, then `php artisan test` → green.

### 4.5 Switch the remote and push

1. `git remote rename origin bitbucket`
2. `git remote add origin https://github.com/dananghel89/dev-blog.git`
3. `git config user.email` → must now print `7040327+dananghel89@users.noreply.github.com`. It comes from `~/.gitconfig` `includeIf "hasconfig:remote.*.url:https://github.com/**"` → `~/.gitconfig-github`.
4. `git push -u origin master`. This is a normal push into an empty repo, so no force is needed.
5. `git branch -vv` → `master` tracks `origin/master`.

### 4.6 ⏸ You: check on GitHub (still private)

- [ ] The commit count matches. Every commit shows your avatar, linked to `dananghel89`.
- [ ] Open a few commits: the dates are original; the messages are as in section 3.
- [ ] The README (review-plan R23, branding-plan credits) renders correctly, including the Blogy / Untree.co credit with the CC BY 3.0 link.
- [ ] `.env.example` contains no real values (no real email, no password, no social URLs; the local `dev-blog.test` URL is fine).

### 4.7 Local cleanup

1. `git remote remove bitbucket` (the local link only; the Bitbucket repo still exists).
2. `git update-ref -d refs/original/refs/heads/master`, then delete `.git-rewrite/` if it was left behind.
3. `git reflog expire --expire=now --all`, then `git gc --prune=now`.
4. Re-check: `git log --all --format='%ae' | sort -u` → only the noreply address.

### 4.8 ⏸ You: make it public

1. Check the repo **as a visitor** (logged out / incognito):
   - [ ] the files, README and commit list look right
   - [ ] no personal data in the site content (`resources/views/**`): contact details, other people's social handles, phone numbers
   - [ ] **no original Blogy template files** anywhere in the history: `git log --all --name-only --format= | grep -E "\.html$|prepros|icomoon|flaticon|hero_[0-9]|img_[0-9]_|person_[0-9]"` → nothing
   - [ ] the adapted theme files (`resources/scss/theme/*`, `resources/js/theme/*`) keep the Untree.co header comment
   - [ ] `.env.example` contains no real values
2. Settings → General → Danger Zone → **Change visibility → Public**.
3. Optional: add a repo description and topics (`laravel`, `blog`, `bootstrap`, `livewire`, `filament`). Don't use the topic or name "blogy" or "template": the repo is an app, not a redistribution of the template. No license file, as in agency-cms, so GitHub shows no license.

### 4.9 ⏸ You: delete the Bitbucket repo (browser)

**Only after 4.6 and 4.8 are done.** The Bitbucket repo still holds the original history with your gmail address and the old brand.

1. Bitbucket → `dan-anghel/dev-blog` → Repository settings → **Delete repository**.
2. Delete the local bundle `../dev-blog-pre-github-*.bundle` too (it also contains the gmail address and the old brand), and the dry-run clone `../dev-blog-dryrun`. Optionally keep it until the Laravel 13 upgrade is merged.

### 4.10 Docs and follow-up

1. In `CLAUDE.md`: the repo is on GitHub (`origin`, branch `master`); commits use the noreply identity automatically; commit messages are one-line `type: description`.
2. The upgrade plan works on the GitHub `master`. Check `git config user.email` before its first commit.

## 5. Checklist (end state)

- [ ] `git remote -v` → only `origin = https://github.com/dananghel89/dev-blog.git`
- [ ] All commits use the noreply address and one-line `type: description` messages
- [ ] No gmail address in the history
- [ ] The GitHub repo is public, with Issues/Wiki/Projects/Discussions off
- [ ] The Bitbucket repo is deleted; the local bundle is deleted (or kept until after the upgrade)
- [ ] `php artisan test` green on the GitHub `master`

## 6. Rollback

| Situation | Action |
|---|---|
| Rewrite looks wrong (before push) | `git reset --hard refs/original/refs/heads/master`, or restore from the bundle: `git fetch ../dev-blog-pre-github-*.bundle master:master --force` (after `git checkout --detach`) |
| Push went wrong (repo still private) | Delete and recreate the GitHub repo in the browser, then push again. **No force-push without asking** |
| Anything else before 4.9 | Bitbucket is untouched and stays the source of truth until it is deleted |
