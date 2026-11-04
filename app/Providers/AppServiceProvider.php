<?php

namespace App\Providers;

use App\Support\SiteData;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureViewComposers();

        Paginator::useBootstrapFive();
    }

    /**
     * Data for the shared layout partials (Blogy theme header, footer and blog sidebar).
     */
    protected function configureViewComposers(): void
    {
        View::composer('partials.header', fn ($view) => $view->with([
            'navCategories' => SiteData::navCategories(),
        ]));

        View::composer('partials.footer', fn ($view) => $view->with([
            'footerPosts' => SiteData::recentPosts(),
        ]));

        View::composer('blog.partials.sidebar', fn ($view) => $view->with([
            'sidebarPosts' => SiteData::recentPosts(),
            'sidebarCategories' => SiteData::sidebarCategories(),
            'sidebarTags' => SiteData::sidebarTags(),
        ]));
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
