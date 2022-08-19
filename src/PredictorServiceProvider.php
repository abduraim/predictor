<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Console\InstallCommand;
use Closure;
use Illuminate\Support\ServiceProvider;

class PredictorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {

            $commands = [
                InstallCommand::class,
            ];

            $this->commands($commands);
        }
    }
}