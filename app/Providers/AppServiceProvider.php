<?php

namespace App\Providers;

use App\Dto\SourceApiConfig;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(SourceApiConfig::class)
            ->needs('$host')
            ->giveConfig("app.source_api.host");
        $this->app->when(SourceApiConfig::class)
            ->needs('$port')
            ->giveConfig("app.source_api.port");
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
