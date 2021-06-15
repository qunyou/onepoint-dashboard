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
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->mergeConfigFrom(__DIR__.'/config/dashboard.php', 'dashboard');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'dashboard');

        // 發佈至 public 目錄的指令
        // php artisan vendor:publish --tag=public --force
        // php artisan vendor:publish --force
        // $this->publishes([
        //     __DIR__.'/Publishes/public/assets/dashboard.zip' => public_path('assets'),
        // ], 'public');

        $this->publishes([
            __DIR__.'/Publishes/public/vendor.zip' => public_path('/'),
        ], 'public/vendor');

        // $this->publishes([
        //     __DIR__.'/Publishes/public/filemanager' => public_path('filemanager'),
        // ], 'filemanager');

        // $this->publishes([
        //     __DIR__.'/Publishes/config' => config_path('/'),
        // ], 'config-filemanager');

        $this->publishes([
            __DIR__.'/Publishes/app/Providers' => app_path('Providers'),
        ], 'providers');

        // $this->publishes([
        //     __DIR__.'/Publishes/custom' => base_path('custom'),
        // ], 'custom');

        $this->publishes([
            __DIR__.'/Publishes/database' => base_path('database'),
        ], 'database');
    }
}
