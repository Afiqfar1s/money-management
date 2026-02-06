<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // Render terminates TLS at the edge and forwards requests to the container.
        // If Laravel doesn't correctly detect HTTPS, it may generate http:// asset URLs,
        // which browsers will block as mixed-content.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Make current company available to all views
        View::composer('layouts.app', \App\View\Composers\CompanyComposer::class);
    }
}
