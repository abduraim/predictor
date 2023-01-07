<?php

namespace Abduraim\Predictor\Repositories;


use Abduraim\Predictor\Exceptions\NeuronConnection\NeronConnectionDuplicateException;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Illuminate\Support\Carbon;

class NeuronConnectionRepository
{
    /**
     * Создание связи нейронов
     *
     * @param NeuronClusterConnection $neuronClusterConnection Связь кластеров нейронов
     * @param array $neuronIds Массив id'шек нейронов
     * @return void
     */
    public function store(NeuronClusterConnection $neuronClusterConnection, array $neuronIds)
    {
        if (
            $neuronClusterConnection
                ->neuron_connections()
                ->whereJsonContains('neurons', $neuronIds)
                ->exists()
        ) {
            throw new NeronConnectionDuplicateException();
        }

        return $neuronClusterConnection
            ->neuron_connections()
            ->create([
                'neurons' => $neuronIds,
                'status' => (rand(1, 10) > 3 ? true : false),
                'weight' => rand(1, 99),
                'updated_at' => Carbon::now(),
            ]);
    }
}