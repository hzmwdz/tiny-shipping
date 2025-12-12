<?php

namespace Hzmwdz\TinyShipping;

use Illuminate\Support\ServiceProvider;

class TinyShippingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/tiny-shipping.php', 'tiny-shipping');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/database/migrations');

        $this->loadTranslationsFrom(dirname(__DIR__) . '/resources/lang', 'tiny-shipping');

        $this->publishes([
            dirname(__DIR__) . '/config/tiny-shipping.php' => $this->app->configPath('tiny-shipping.php')
        ], 'tiny-shipping-config');

        $this->publishes([
            dirname(__DIR__) . '/database/migrations' => $this->app->databasePath('migrations')
        ], 'tiny-shipping-database');

        $this->publishes([
            dirname(__DIR__) . '/resources/lang' => $this->app->resourcePath('lang/vendor/tiny-shipping'),
        ], 'tiny-shipping-translations');
    }
}
