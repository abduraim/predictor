<?php

namespace Abduraim\Predictor\Repositories;

use Abduraim\Predictor\Exceptions\NeuronClusterConnection\NeuronClusterConnectionDuplicateException;
use Abduraim\Predictor\Exceptions\NeuronClusterConnection\NeuronClusterConnectionIdenticalClustersException;
use Abduraim\Predictor\Exceptions\NeuronClusterConnection\NeuronClusterConnectionNeuronClusterMissingException;
use Abduraim\Predictor\Exceptions\NeuronConnection\NeronConnectionDuplicateException;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Models\NeuronConnection;
use Abduraim\Predictor\Services\HelperService;
use Illuminate\Support\Facades\DB;
use Spatie\Ignition\Ignition;

class NeuronClusterConnectionRepository
{
    /**
     * Создание новой связи кластеров нейронов
     *
     * @param int $neuronClusterId1
     * @param int $neuronClusterId2
     * @return void
     */
    public function store(int $neuronClusterId1, int $neuronClusterId2)
    {
        // Проверяем валидность данных
        if ($neuronClusterId1 === $neuronClusterId2) {
            throw new NeuronClusterConnectionIdenticalClustersException();
        }
        
        if (
            NeuronCluster::query()->where('id', $neuronClusterId1)->doesntExist() ||
            NeuronCluster::query()->where('id', $neuronClusterId2)->doesntExist()
        ) {
            throw new NeuronClusterConnectionNeuronClusterMissingException();
        }
        
        if (NeuronClusterConnection::query()->whereJsonContains('clusters', [$neuronClusterId1, $neuronClusterId2])->exists()) {
            throw new NeuronClusterConnectionDuplicateException();
        }

        // Создаем записи в БД
        DB::transaction(function () use ($neuronClusterId1, $neuronClusterId2) {
            // Создаем запись о связи кластеров
            $neuronClusterConnection = NeuronClusterConnection::query()
                ->create([
                    'clusters' => [$neuronClusterId1, $neuronClusterId2],
                    'status' => true,
                    'weight' => 1,
                ]);

            // Создаем записи о связях нейронов этих кластеров
            $this->syncNeuronConnections($neuronClusterConnection);
        });
    }

    /**
     * Создание все связей неронов принимаемой связи кластеров нейронов
     * 
     * @param NeuronClusterConnection $neuronClusterConnection Связь кластеров нейронов
     * @return void
     */
    public function syncNeuronConnections(NeuronClusterConnection $neuronClusterConnection)
    {
        $neuronIdsByClusters = collect($neuronClusterConnection->clusters)
            ->map(function ($clusterId) {
                return NeuronCluster::query()
                    ->findOrFail($clusterId)
                    ->neurons()
                    ->pluck('id');
            });

        $neuronIdPairs = $neuronIdsByClusters
            ->first()
            ->crossJoin($neuronIdsByClusters->last())
            ->map(function ($neuronIds) use ($neuronClusterConnection) {
                return             [
                    'neuron_cluster_connection_id' => $neuronClusterConnection->getKey(),
                    'neurons' => json_encode($neuronIds),
                    'status' => (rand(1, 10) > 3 ? true : false),
                    'weight' => rand(1, 99),
                ];
            })
            ->chunk(1000)
            ->each(function ($insertChunk, $key) {
                echo "{$key}\n";
                NeuronConnection::query()->insert($insertChunk->toArray());
            });

//        $neuronIdPairs
//            ->each(function($neuronIds, $key) use ($neuronClusterConnection, $count) {
//                echo "{$count}/{$key}\n";
//                try {
//                    (new NeuronConnectionRepository())->store($neuronClusterConnection, $neuronIds);
//                } catch (NeronConnectionDuplicateException) {
//                    // do nothing
//                }
//            });
    }
}