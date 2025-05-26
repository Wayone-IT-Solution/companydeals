<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

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
            $request = request(); // ✅ Define $request here

            SymfonyRequest::setTrustedProxies(
                [$request->getClientIp()],
                SymfonyRequest::HEADER_X_FORWARDED_ALL
            );

            URL::forceScheme('https');
        }
    }
}
