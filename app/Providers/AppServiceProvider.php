<?php

namespace App\Providers;

use App\Services\CoinbaseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CoinbaseService::class, function () {
           return CoinbaseService::getInstance();
        });
    }
}
