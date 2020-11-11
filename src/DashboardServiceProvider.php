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
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'dashboard');
        
        // todo 有問題，讀取不到
        // $this->mergeConfigFrom(__DIR__.'/config/dashboard.php', 'dashboard');

        // 發佈至 public 目錄的指令
        // php artisan vendor:publish --tag=public --force
        // php artisan vendor:publish --force
        $this->publishes([
            __DIR__.'/Publishes/public/assets/dashboard' => public_path('assets/dashboard'),
        ], 'public');

        // $this->publishes([
        //     __DIR__.'/Publishes/public/vendor/laravel-filemanager' => public_path('vendor/laravel-filemanager'),
        // ], 'public-vendor');

        $this->publishes([
            __DIR__.'/Publishes/app/Http' => app_path('Http'),
        ], 'http');

        // $this->publishes([
        //     __DIR__.'/Publishes/custom' => base_path('custom'),
        // ], 'custom');

        // $this->publishes([
        //     __DIR__.'/Publishes/database' => base_path('database'),
        // ], 'custom');
    }
}
