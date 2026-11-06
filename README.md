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

Set your own values in `.env` (never committed). The site identity has neutral defaults in `config/project.php`. These keys are optional and not listed in `.env.example`: add any of them to your `.env` to override a default.

| Key | Used for |
|---|---|
| `APP_NAME` | Site name: logo, page titles, footer, emails |
| `BLOG_TAGLINE` | Default meta description, About cover |
| `BLOG_AUTHOR` | Meta author, the About greeting |
| `CONTACT_EMAIL` | Recipient of the contact form |
| `SOCIAL_GITHUB`, `SOCIAL_LINKEDIN`, `SOCIAL_X`, `SOCIAL_STACKOVERFLOW` | Profile URLs; empty networks are hidden |

To show your photo on the About page, add `public/images/avatar.jpg`.

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

The project's own code, icon and images are proprietary (all rights reserved); the theme and libraries above keep their own licenses.
