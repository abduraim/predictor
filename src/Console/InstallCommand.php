<?php

namespace Abduraim\Predictor\Console;

use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\PredictorService;
use App\Models\Gender;
use App\Models\Present;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'predictor:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Postie resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(PredictorService $predictorService)
    {

        $present = Present::find(1);
        
        $predictorService->getVariants(new NeuronableCollection([$present]));
        
        dd($present);
        
        

//
//        $predictorService->makeNeuronConnection(
//            neuronableCollection: new NeuronableCollection([Gender::find(8), Present::find(1)])
//        );
//        $predictorService->makeNeuronConnection(
//            neuronableCollection: new NeuronableCollection([Gender::find(9), Present::find(1)])
//        );
//        $predictorService->makeNeuronConnection(
//            neuronableCollection: new NeuronableCollection([Gender::find(11), Present::find(1)])
//        );
        dd();
        $predictorService->makeNeuronConnection(neuronableCollection: [Gender::first(), Present::first()]);

        dd('adsf', $result);
        $predictorService->sync();


        $this->comment('test predictor');
//        $this->comment('Publishing Postie Service Provider...');
//        $this->callSilent('vendor:publish', ['--tag' => 'postie-provider']);
//
//        $this->comment('Publishing Postie Assets...');
//        $this->callSilent('vendor:publish', ['--tag' => 'postie-assets']);
//
//        $this->comment('Publishing Postie Configuration...');
//        $this->callSilent('vendor:publish', ['--tag' => 'postie-config']);
//
//        $this->comment('Publishing Postie Migrations...');
//        $this->callSilent('vendor:publish', ['--tag' => 'postie-migrations']);
//
//        $this->registerPostieServiceProvider();
//
//        $this->info('Postie scaffolding installed successfully.');
    }

    /**
     * Register the Folks service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerPostieServiceProvider()
    {
//        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());
//
//        $appConfig = file_get_contents(config_path('app.php'));
//
//        if (Str::contains($appConfig, $namespace.'\\Providers\\PostieServiceProvider::class')) {
//            return;
//        }
//
//        file_put_contents(config_path('app.php'), str_replace(
//            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
//            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\PostieServiceProvider::class,".PHP_EOL,
//            $appConfig
//        ));
//
//        file_put_contents(app_path('Providers/PostieServiceProvider.php'), str_replace(
//            "namespace App\Providers;",
//            "namespace {$namespace}\Providers;",
//            file_get_contents(app_path('Providers/PostieServiceProvider.php'))
//        ));
    }
}
