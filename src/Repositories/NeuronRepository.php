<?php

namespace Abduraim\Predictor\Repositories;

use Abduraim\Predictor\Exceptions\NeuronConnection\NeronConnectionDuplicateException;
use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Models\NeuronConnection;
use Abduraim\Predictor\Services\HelperService;
use Illuminate\Support\Facades\DB;

class NeuronRepository
{
    /**
     * Создание нейрона для сущности
     *
     * @param Neuronable $model Сущность
     * @return void
     */
    public function store(Neuronable $model)
    {
        // Создаем записи в БД
        DB::transaction(function () use ($model) {

            // Создаем нейрон
            $neuron = $model->neuron()->create(['options' => []]);

            // Определяем название класса
            $class = HelperService::getPolymorphClassAliasIfExist(get_class($model));

            // Определяем кластер
            /** @var NeuronCluster $neuronCluster */
            $neuronCluster = NeuronCluster::query()
                ->where('neuronable_type', $class)
                ->first();

            // Создаем связи нейронов
            if ($neuronCluster) {

                $neuronCluster
                    ->targetableNeuronClusterConnections
                    ->each(function (NeuronClusterConnection $neuronClusterConnection) use ($neuron) {
                        $neuronClusterConnection
                            ->determinantNeuronCluster
                            ->neurons
                            ->each(function (Neuron $determinantNeuron) use ($neuronClusterConnection, $neuron) {
                                try {
                                    (new NeuronConnectionRepository())->store($neuronClusterConnection, $determinantNeuron, $neuron);
                                } catch (NeronConnectionDuplicateException $exception) {
                                    // do nothing
                                }
                            });
                    });

                $neuronCluster
                    ->determinatableNeuronClusterConnections
                    ->each(function (NeuronClusterConnection $neuronClusterConnection) use ($neuron) {
                        $neuronClusterConnection
                            ->targetNeuronCluster
                            ->neurons
                            ->each(function (Neuron $targetNeuron) use ($neuronClusterConnection, $neuron) {
                                try {
                                    (new NeuronConnectionRepository())
                                        ->store($neuronClusterConnection, $neuron, $targetNeuron);
                                } catch (NeronConnectionDuplicateException $exception) {
                                    // do nothing
                                }
                            });
                    });
            }

        });

    }
}