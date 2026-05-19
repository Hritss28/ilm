<?php

namespace App\Providers;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\News;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use App\Policies\NewsPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(News::class, NewsPolicy::class);

        // Register ActivityLog observer for admin panel models
        News::observe(ActivityLogObserver::class);
        Advertisement::observe(ActivityLogObserver::class);
        User::observe(ActivityLogObserver::class);
        Category::observe(ActivityLogObserver::class);
    }
}
