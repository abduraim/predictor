<?php

namespace Abduraim\Predictor\Console;

use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Models\NeuronConnection;
use Abduraim\Predictor\PredictorService;
use Abduraim\Predictor\Repositories\NeuronClusterConnectionRepository;
use Abduraim\Predictor\Repositories\NeuronConnectionRepository;
use Abduraim\Predictor\Services\HelperService;
use App\Models\Cost;
use App\Models\Gender;
use App\Models\Holiday;
use App\Models\Other;
use App\Models\Person;
use App\Models\Present;
use App\Models\Weekday;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'predictor:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(PredictorService $predictorService)
    {
//        $count = 7;
//        $class = Weekday::class;
//        $alias = HelperService::getPolymorphClassAliasIfExist($class);
//
//        $bar = $this->output->createProgressBar($count);
//
//        $bar->start();
//        for ($i = 0; $i < $count; $i++) {
//            $class::query()->create(['name' => "{$alias} {$i}"]);
//            $bar->advance();
//        }
//        $bar->finish();
//        dd('end');

//        $determinantNeuronCluster = NeuronCluster::find(2);
//        $targetNeuronCluster = NeuronCluster::find(6);
//        (new NeuronClusterConnectionRepository())->store($determinantNeuronCluster, $targetNeuronCluster);
//
//        dd('adsf');


        
        

//        $present = Present::query()->create(['name' => 'Зонт']);
//        $present = Present::query()->create(['name' => 'Автомобиль']);
//        $present = Present::query()->create(['name' => 'Мяч']);
//        $present = Present::query()->create(['name' => 'Плюшевый мишка']);
//        $present = Present::query()->create(['name' => 'Монополия']);
//        $present = Present::query()->create(['name' => 'Сапоги']);
//        $present = Present::query()->create(['name' => 'Маска']);
//        $present = Present::query()->create(['name' => 'Веер']);
//        $present = Present::query()->create(['name' => 'Картина']);
//        $present = Present::query()->create(['name' => 'Фоторамка']);
//        $present = Present::query()->create(['name' => 'Футболка']);
//        $gender = Gender::query()->create(['name' => 'Мужской']);
//        $gender = Gender::query()->create(['name' => 'Женский']);
//        $person = Person::query()->create(['name' => 'Мама']);
//        $person = Person::query()->create(['name' => 'Папа']);
//        $person = Person::query()->create(['name' => 'Бабушка']);
//        $person = Person::query()->create(['name' => 'Дедушка']);
//        $person = Person::query()->create(['name' => 'Брат']);
//        $person = Person::query()->create(['name' => 'Сестра']);
//
//        dd('asdf');



        $resultIds = $predictorService->predict(
            6,
            [
                [
                    'neuron_cluster_id' => 1,
                    'neuron_id' => 10012,
                ],
                [
                    'neuron_cluster_id' => 2,
                    'neuron_id' => 10023,
                ],
                [
                    'neuron_cluster_id' => 3,
                    'neuron_id' => 10064,
                ],
                [
                    'neuron_cluster_id' => 4,
                    'neuron_id' => 10095,
                ],
                [
                    'neuron_cluster_id' => 5,
                    'neuron_id' => 10117,
                ],
                [
                    'neuron_cluster_id' => 7,
                    'neuron_id' => 10128,
                ],
            ]
        );
//
//        // Загружаем данные о результатах
//        $resultNeuronIdsString = $resultIds->implode(',');
//        Neuron::query()
//            ->whereIntegerInRaw('id', $resultIds)
//            ->orderByRaw(DB::raw("FIELD(id, {$resultNeuronIdsString})"))
//            ->with('neuronable')
//            ->each(function (Neuron $neuron) {
//                echo "[{$neuron->getKey()}] {$neuron->neuronable->name} \n";
//            });
//
//        dd();


        


//        $present = Present::find(1);
//
//        $predictorService->getVariants(new NeuronableCollection([$present]));
//
//        dd($present);


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
//        dd();
//        $predictorService->makeNeuronConnection(neuronableCollection: [Gender::first(), Present::first()]);
//
//        dd('adsf', $result);
//        $predictorService->sync();
//
//
//        $this->comment('test predictor');


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
