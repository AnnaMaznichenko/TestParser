<?php

namespace App\Providers;

use App\Services\IncomeParser;
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
        $this->app->when(IncomeParser::class)
            ->needs('$host')
            ->giveConfig("app.source_api.host");
        $this->app->when(IncomeParser::class)
            ->needs('$port')
            ->giveConfig("app.source_api.port");
        $this->app->when(IncomeParser::class)
            ->needs('$key')
            ->giveConfig("app.source_api.key");
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
