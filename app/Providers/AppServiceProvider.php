<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Complaint;
use App\Observer\ComplaintObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (
            $this->app->environment('local') &&
            class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)
        ) {
            $this->app->register(
                \App\Providers\TelescopeServiceProvider::class
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Complaint::observe(
            ComplaintObserver::class
        );

        config()->set('cors.paths', ['api/*', 'sanctum/csrf-cookie']);
        config()->set('cors.allowed_origins', ['http://localhost:8000']);
        config()->set('cors.allowed_methods', ['*']);
        config()->set('cors.allowed_headers', ['*']);
        config()->set('cors.supports_credentials', true);
    }
}
