<?php

namespace AkkiIo\LaravelNovaAssets;

use AkkiIo\LaravelNovaAssets\Console\NovaMixCommand;
use Illuminate\Support\ServiceProvider;

class LaravelNovaAssetsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->offerPublishing();
        $this->registerCommands();
    }

    /**
     * Set up the resource publishing groups for laravel-nova-assets.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-nova-assets.php' => config_path('laravel-nova-assets.php'),
            ], 'laravel-nova-assets-config');
            $this->publishes([
                __DIR__.'/../resources/webpack.mix.nova.js' => base_path('webpack.mix.nova.js'),
            ], 'laravel-nova-assets-webpack');
        }
    }

    /**
     * Register the Laracube Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                NovaMixCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-nova-assets.php', 'laravel-nova-assets');
    }
}
