<?php

namespace W360\SecureData;

use Illuminate\Support\ServiceProvider;
use W360\SecureData\Commands\SecureEncryptCommand;
use W360\SecureData\Commands\SecureKeyCommand;

class SecureDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->registerCommands();
    }


    /**
     *
     */
    public function registerCommands(){
        if ($this->app->runningInConsole()) {
            $this->commands([
                SecureKeyCommand::class,
                SecureEncryptCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'secure-data');
    }


    /**
     * register publishing
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('secure-data.php'),
            ], 'config');
        }
    }
}
