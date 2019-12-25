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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'dashboard');

        // 發佈至 public 目錄的指令
        // php artisan vendor:publish --tag=public --force
        // php artisan vendor:publish --force
        $this->publishes([
            __DIR__.'/Publishes/public/assets/dashboard' => public_path('assets/dashboard'),
        ], 'public');

        $this->publishes([
            __DIR__.'/Publishes/app/Entities' => app_path('Entities'),
        ], 'entities');

        $this->publishes([
            __DIR__.'/Publishes/app/Repositories' => app_path('Repositories'),
        ], 'repositories');

        $this->publishes([
            __DIR__.'/Publishes/app/Http' => app_path('Http'),
        ], 'http');

        $this->publishes([
            __DIR__.'/Publishes/resources/views/dashboard' => resource_path('views/dashboard'),
        ], 'views');

        $this->publishes([
            __DIR__.'/Publishes/resources/lang' => resource_path('lang'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/Publishes/custom' => base_path('custom'),
        ], 'custom');

        $this->publishes([
            __DIR__.'/Publishes/routes' => base_path('routes'),
        ], 'routes');

        $this->publishes([
            __DIR__.'/Publishes/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/Publishes/database/seeds' => database_path('seeds'),
        ], 'seeds');
    }
}
