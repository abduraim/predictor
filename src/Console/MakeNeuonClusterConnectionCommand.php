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
    {determinantClass? : Class or morph alias} 
    {targetClass? : Class or morph alias}';

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
        $determinantType = $this->argument('determinantClass') ?: $this->anticipate('Введите класс/alias кластера определителя', function ($input) {
            return NeuronCluster::query()->pluck('neuronable_type')->toArray();
        });

        $targetType = $this->argument('targetClass') ?: $this->anticipate('Введите класс/alias кластера определяемого', function ($input) {
            return NeuronCluster::query()->pluck('neuronable_type')->toArray();
        });
        
        $determinantNeuronCluster = NeuronCluster::query()->where('neuronable_type', $determinantType)->firstOrFail();
        $targetNeuronCluster = NeuronCluster::query()->where('neuronable_type', $targetType)->firstOrFail();
        
        (new NeuronClusterConnectionRepository())->store($determinantNeuronCluster, $targetNeuronCluster);

        $this->info('Связь кластеров нейронов успешно создана');
    }
}
