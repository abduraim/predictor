<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Console\InstallCommand;
use Abduraim\Predictor\Console\MakeNeuonClusterConnectionCommand;
use Abduraim\Predictor\Console\SyncCommand;
use Abduraim\Predictor\Console\TestCommand;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class PredictorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerMigrations();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('PREDICTOR_PATH')) {
            define('PREDICTOR_PATH', realpath(__DIR__.'/../'));
        }

        $this->configure();
    }

    /**
     * Setup the configuration for Horizon.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/predictor.php', 'predictor'
        );
    }

    /**
     * Register the Horizon routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
//            'domain' => config('predictor.domain', null),
            'prefix' => config('predictor.path'),
            'namespace' => 'Abduraim\Predictor\Http\Controllers',
            'middleware' => config('predictor.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Horizon resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'predictor');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            PREDICTOR_PATH.'/public' => public_path('vendor/predictor'),
        ], ['predictor-assets', 'laravel-assets']);
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                SyncCommand::class,
                MakeNeuonClusterConnectionCommand::class,
                TestCommand::class,
            ]);
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'predictor-migrations');

            $this->publishes([
                __DIR__.'/../config/predictor.php' => config_path('predictor.php'),
            ], 'predictor-config');
        }
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}