<?php

namespace Onepoint\Dashboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Onepoint\Dashboard\View\Components\SideItem;

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

        // dd(Side::class);
        // Blade::component('side-item', SideItem::class);
        Blade::component('side-item', 'SideItem');

        // $this->callAfterResolving(BladeCompiler::class, function () {
        //     Blade::component('dashboard::side-item', SideItem::class);
        // });

        // 發佈至 public 目錄的指令
        // php artisan vendor:publish --tag=public --force
        // php artisan vendor:publish --force

        $this->publishes([
            __DIR__.'/Publishes/public' => public_path('/'),
        ], 'public');

        $this->publishes([
            __DIR__.'/Publishes/app/Providers' => app_path('Providers'),
        ], 'providers');

        $this->publishes([
            __DIR__.'/Publishes/database' => base_path('database'),
        ], 'database');

        $this->publishes([
            __DIR__.'/Publishes/packages' => base_path('packages'),
        ], 'packages');
    }

    // protected function configureComponents()
    // {
	// 	$this->callAfterResolving(BladeCompiler::class, function () {
	// 		$this->registerComponent('side-item');
	// 	});
	// }

    // protected function registerComponent(string $component)
    // {
    //     Blade::component('dashboard::components.'.$component, 'dashboard-'.$component);
    // }
}
