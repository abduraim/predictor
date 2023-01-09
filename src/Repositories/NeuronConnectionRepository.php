<?php

namespace Abduraim\Predictor\Repositories;


use Abduraim\Predictor\Exceptions\NeuronConnection\NeronConnectionDuplicateException;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Illuminate\Support\Carbon;

class NeuronConnectionRepository
{
    /**
     * Создание связи нейронов
     * 
     * @param NeuronClusterConnection $neuronClusterConnection Связь кластеров нейронов
     * @param Neuron $determinantNeuron Нейрон определитель
     * @param Neuron $targetNeuron Определяемый нейрон
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(
        NeuronClusterConnection $neuronClusterConnection,
        Neuron                  $determinantNeuron,
        Neuron                  $targetNeuron
    )
    {
        return $neuronClusterConnection
            ->neuron_connections()
            ->create([
                'determinant_neuron_id' => $determinantNeuron->getKey(),
                'target_neuron_id' => $targetNeuron->getKey(),
                'status' => false,
                'weight' => 1,
                'updated_at' => Carbon::now(),
            ]);
    }
}