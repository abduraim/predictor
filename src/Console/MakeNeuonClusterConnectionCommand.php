<?php

namespace Abduraim\Predictor\Console;

use Abduraim\Predictor\Models\NeuronCluster;
use Illuminate\Console\Command;
use Abduraim\Predictor\Repositories\NeuronClusterConnectionRepository;

class MakeNeuonClusterConnectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'predictor:make-neuron-cluster-connection 
    {class1? : Class or morph alias} 
    {class2? : Class or morph alias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make neuron cluster connection';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $class1 = $this->argument('class1') ?: $this->anticipate('Введите класс/alias первого кластера', function ($input) {
            return NeuronCluster::query()->pluck('neuronable_type')->toArray();
        });

        $class2 = $this->argument('class2') ?: $this->anticipate('Введите класс/alias второго кластера', function ($input) {
            return NeuronCluster::query()->pluck('neuronable_type')->toArray();
        });
        
        $neuronCluster1 = NeuronCluster::query()->where('neuronable_type', $class1)->first();
        $neuronCluster2 = NeuronCluster::query()->where('neuronable_type', $class2)->first();
        
        (new NeuronClusterConnectionRepository())->store($neuronCluster1->getKey(), $neuronCluster2->getKey());

        $this->info('Связь кластеров нейронов успешно создана');
    }
}
