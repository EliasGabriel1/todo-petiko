<?php

namespace App\Providers;

use App\Adapters\AuthServiceAdapter;
use App\Adapters\AuthServiceAdapterInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceAdapterInterface::class, AuthServiceAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
