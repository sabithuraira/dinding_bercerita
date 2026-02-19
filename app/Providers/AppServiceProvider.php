<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Use HTTPS for URLs when APP_URL is https (avoids "not secure" warning on form submit)
        if (config('app.env') !== 'local' && str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
