<?php

namespace W360\ImageStorage;

use Illuminate\Support\ServiceProvider;

class ImageStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
       $this->registerResources();
       $this->registerPublishing();
   }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'image-storage');


        // Register the main class to use with the facade
        $this->app->singleton('imageSt', function () {
            return new ImageService;
        });
    }

    /**
     * register resources
     */
    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * register publishing
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('image-storage.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }
}
