<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;


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
        if (app()->isProduction()) {
            // Trust all proxies & headers
            Request::setTrustedProxies(
                [Request::getClientIp()],
                Request::HEADER_X_FORWARDED_ALL
            );

            URL::forceScheme('https');
        }
    }
}
