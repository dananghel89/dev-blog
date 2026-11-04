# Dev Blog

A personal developer blog about the real problems met while building with PHP, MySQL, HTML, CSS and JavaScript, and how they were solved.

Built with Laravel 12, Livewire 4 and Vite.

> There is no admin yet: posts come from the database seeders. A Filament admin for writing posts is planned (see `docs/finish-project-plan.md`).

## Requirements

- PHP 8.4, Composer
- MySQL 8 (InnoDB)
- Node 20+

## Setup

```bash
# create an empty "dev_blog" database first, then:
composer setup                     # install, copy .env, key, migrate, npm install + build
php artisan migrate:fresh --seed   # demo categories, tags and posts
composer dev                       # local server, queue, logs and Vite
```

Set your own values in `.env` (never committed), e.g. `CONTACT_EMAIL` for the contact form.

## Tests

```bash
php artisan test tests/Feature/Site
```

Tests run on an in-memory SQLite database, so they never touch your MySQL data.

## Credits

- Design based on the **Blogy** template by [Untree.co](https://untree.co), licensed under [CC BY 3.0](https://creativecommons.org/licenses/by/3.0/), modified. The template itself is not redistributed here; only its adapted styles and scripts are part of this application.
- [Bootstrap](https://getbootstrap.com) and [Bootstrap Icons](https://icons.getbootstrap.com) (MIT)
- [Work Sans](https://fontsource.org/fonts/work-sans) (SIL Open Font License 1.1)
- [AOS](https://github.com/michalsnik/aos) (MIT)
