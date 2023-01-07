<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Console\InstallCommand;
use Abduraim\Predictor\Console\MakeNeuonClusterConnectionCommand;
use Abduraim\Predictor\Console\SyncCommand;
use Abduraim\Predictor\Console\TestCommand;
use Closure;
use Illuminate\Support\ServiceProvider;

class PredictorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerMigrations();
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