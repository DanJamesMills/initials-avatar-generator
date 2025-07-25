<?php

namespace DanJamesMills\InitialsAvatarGenerator;

use Illuminate\Support\ServiceProvider;

class InitialsAvatarGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('initials-avatar-generator.php'),
            ], 'initials-avatar-generator-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'initials-avatar-generator');

        // Register the main class to use with the facade
        $this->app->singleton('initials-avatar-generator', function () {
            return new InitialsAvatarGenerator;
        });
    }
}
