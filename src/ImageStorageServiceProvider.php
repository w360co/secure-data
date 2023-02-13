<?php

namespace W360\ImageStorage;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

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
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'image-storage');


        // Register the main class to use with the facade
        $this->app->singleton('imageSt', function () {
            return new ImageService;
        });

        $this->callAfterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $this->registerBladeExtensions($bladeCompiler);
        });
    }

    /**
     * @param $model
     * @return bool
     */
    public static function bladeMethodWrapper($model)
    {
        return isset($model->images) && is_array($model->images) ? $model->images()->count() > 0 : isset($model->images->name);
    }

    /**
     * @param $bladeCompiler
     */
    protected function registerBladeExtensions($bladeCompiler)
    {
        $bladeCompiler->directive('hasimage', function ($arguments) {
            return "<?php if(\\W360\\ImageStorage\\ImageStorageServiceProvider::bladeMethodWrapper('hasImage', {$arguments})): ?>";
        });
        $bladeCompiler->directive('endhasimage', function () {
            return '<?php endif; ?>';
        });
    }

    /**
     * register resources
     */
    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * register publishing
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('image-storage.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }
}
