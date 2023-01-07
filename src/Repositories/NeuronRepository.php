<?php

namespace Abduraim\Predictor\Repositories;

use Abduraim\Predictor\Exceptions\NeuronConnection\NeronConnectionDuplicateException;
use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Neuron;
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

            // Проходим по всем связям кластеров нейронов
            NeuronClusterConnection::query()
                ->whereJsonContains('clusters', $class)
                ->get()
                ->each(function (NeuronClusterConnection $neuronClusterConnection) use ($class, $neuron) {

                    // И создаем связи нейронов для нового нейрона
                    Neuron::query()
                        ->where('neuronable_type', $neuronClusterConnection->getOppositeCluster($class))
                        ->pluck('id')
                        ->crossJoin($neuron->getKey())
                        ->each(function ($neuronIds) use ($neuronClusterConnection) {
                            try {
                                (new NeuronConnectionRepository())->store($neuronClusterConnection, $neuronIds);
                            } catch (NeronConnectionDuplicateException $exception) {
                                // do nothing
                            }
                        });
                });
        });

    }

    /**
     * Удаление нейрона
     *
     * @param Neuronable $model
     * @return void
     */
    public function destroy(Neuronable $model)
    {
        DB::transaction(function () use ($model) {
            $neuron = $model->neuron;

            // Удаляем связи нейронов
            NeuronConnection::query()
                ->whereJsonContains('neurons', $neuron->getKey())
                ->delete();

            // Удаляем нейрон
            $neuron->delete();
        });

    }
}