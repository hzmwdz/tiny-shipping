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
        $bindings = [
            Contracts\CalculateShipping::class => Actions\CalculateShipping::class,
            Contracts\CreateShipping::class    => Actions\CreateShipping::class,
            Contracts\DeleteShipping::class    => Actions\DeleteShipping::class,
            Contracts\GetShipping::class       => Actions\GetShipping::class,
            Contracts\GetShippingList::class   => Actions\GetShippingList::class,
            Contracts\UpdateShipping::class    => Actions\UpdateShipping::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
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
            dirname(__DIR__) . '/database/migrations' => $this->app->databasePath('migrations')
        ], 'migrations');

        $this->publishes([
            dirname(__DIR__) . '/resources/lang' => $this->app->resourcePath('lang/vendor/tiny-shipping'),
        ], 'translations');
    }
}
