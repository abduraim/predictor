<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Builders\NeuronClusterConnectionBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Связь кластеров нейронов
 *
 * @property int $id ID
 * @property array $clusters Массив связанных кластеров нейронов
 * @property bool $status Статус
 * @property int $weight Вес связи
 * @property Carbon $created_at Timestamp создания
 * @property Carbon $updated_at Timestamp обновления
 *
 * @property NeuronConnection[] $neuron_connections Связи нейронов
 * 
 * @method static NeuronClusterConnectionBuilder query()
 */
class NeuronClusterConnection extends Model
{
    protected $table = 'neuron_cluster_connections';

    protected $fillable = [
        'clusters',
        'status',
        'weight',
    ];
    
    protected $casts = [
        'clusters' => 'array',
        'status' => 'boolean',
    ];
    
    public function newEloquentBuilder($query): NeuronClusterConnectionBuilder
    {
        return new NeuronClusterConnectionBuilder($query);
    }

    public function neuron_connections()
    {
        return $this->hasMany(NeuronConnection::class, 'neuron_cluster_connection_id');
    }

    /**
     * Возвращает оставшийся класс кластера
     * 
     * @param string $cluster Входящий кластер
     * @return string
     */
    public function getOppositeCluster(string $cluster): string
    {
        return collect($this->clusters)
            ->filter(function ($item) use ($cluster) {
                return $item !== $cluster;
            })
            ->first();
    }
}