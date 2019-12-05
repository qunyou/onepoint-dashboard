<?php

namespace Onepoint\Dashboard;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->make(Controllers\AuthController::class);
        $this->loadViewsFrom(__DIR__ . '/views', 'dashboard');

        // 發佈至 public 目錄的指令
        // php artisan vendor:publish --tag=public --force
        $this->publishes([
            __DIR__.'/assets/dashboard' => public_path('assets/dashboard'),
        ], 'public');

        // 發佈至 views 目錄的指令
        // php artisan vendor:publish
        // $this->publishes([
        //     __DIR__.'/views/dashboard' => resource_path('views/dashboard'),
        // ]);
    }
}
