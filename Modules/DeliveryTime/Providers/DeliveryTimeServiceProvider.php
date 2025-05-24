<?php

namespace Modules\DeliveryTime\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DeliveryTimeServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('DeliveryTime', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('DeliveryTime', 'Config/config.php') => config_path('deliverytime.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DeliveryTime', 'Config/config.php'), 'deliverytime'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/deliverytime');

        $sourcePath = module_path('DeliveryTime', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/deliverytime';
        }, \Config::get('view.paths')), [$sourcePath]), 'deliverytime');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/deliverytime');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'deliverytime');
        } else {
            $this->loadTranslationsFrom(module_path('DeliveryTime', 'Resources/lang'), 'deliverytime');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            $this->loadFactoriesFrom(module_path('DeliveryTime', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
