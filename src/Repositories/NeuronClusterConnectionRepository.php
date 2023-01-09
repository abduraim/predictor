<?php

namespace Abduraim\Predictor\Repositories;

use Abduraim\Predictor\Exceptions\NeuronClusterConnection\NeuronClusterConnectionDuplicateException;
use Abduraim\Predictor\Exceptions\NeuronClusterConnection\NeuronClusterConnectionIdenticalClustersException;
use Abduraim\Predictor\Exceptions\NeuronClusterConnection\NeuronClusterConnectionNeuronClusterMissingException;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Models\NeuronConnection;
use Abduraim\Predictor\Services\HelperService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Ignition\Ignition;

class NeuronClusterConnectionRepository
{
    /**
     * Создание новой связи кластеров нейронов
     *
     * @param NeuronCluster $determinantNeuronCluster Определяющий кластер нейронов
     * @param NeuronCluster $targetNeuronCluster Определяемый кластер нейронов
     * @param bool $status Статус
     * @param int $weight Вес связи
     *
     * @return NeuronClusterConnection
     */
    public function store(
        NeuronCluster $determinantNeuronCluster,
        NeuronCluster $targetNeuronCluster,
        bool          $status,
        int           $weight
    )
    {
        // Проверяем валидность данных
        if ($determinantNeuronCluster->is($targetNeuronCluster)) {
            throw new NeuronClusterConnectionIdenticalClustersException();
        }

        // Создаем записи в БД
        return DB::transaction(function () use ($determinantNeuronCluster, $targetNeuronCluster, $status, $weight) {
            // Создаем запись о связи кластеров
            try {
                $neuronClusterConnection = NeuronClusterConnection::query()
                    ->create([
                        'determinant_cluster_id' => $determinantNeuronCluster->getKey(),
                        'target_cluster_id' => $targetNeuronCluster->getKey(),
                        'status' => $status,
                        'weight' => $weight,
                    ]);
            } catch (QueryException $exception) {
                switch ((int)$exception->getCode()) {
                    case 23000:
                        throw new NeuronClusterConnectionDuplicateException();
                        break;
                }
            }

            // Создаем записи о связях нейронов этих кластеров
            $this->syncNeuronConnections($neuronClusterConnection);

            // Возвращаем созданную связь
            return $neuronClusterConnection;
        });
    }

    /**
     * Создание всех связей нейронов принимаемой связи кластеров нейронов
     *
     * @param NeuronClusterConnection $neuronClusterConnection Связь кластеров нейронов
     * @return void
     */
    public function syncNeuronConnections(NeuronClusterConnection $neuronClusterConnection)
    {
        $result = [];

        $neuronClusterConnection
            ->determinantNeuronCluster
            ->neurons
            ->each(function (Neuron $determinantNeuron) use ($neuronClusterConnection, &$result) {
                $neuronClusterConnection
                    ->targetNeuronCluster
                    ->neurons
                    ->each(function (Neuron $targetNeuron) use ($neuronClusterConnection, $determinantNeuron, &$result) {
//                        dd($determinantNeuron->toArray(), $targetNeuron->toArray());
                        $result[] = [
                            'neuron_cluster_connection_id' => $neuronClusterConnection->getKey(),
                            'determinant_neuron_id' => $determinantNeuron->getKey(),
                            'target_neuron_id' => $targetNeuron->getKey(),
                            'status' => (rand(1, 10) > 3 ? true : false),
                            'weight' => rand(1, 99),
                            'updated_at' => Carbon::now(),
                        ];
                    });
            });

        collect($result)
            ->chunk(1000)
            ->each(function (Collection $chunk) {
                NeuronConnection::query()->insert($chunk->toArray());
            });
    }
}