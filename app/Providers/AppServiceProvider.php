<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Observer\ComplaintObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

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
