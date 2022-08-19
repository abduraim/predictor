<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Builders\NeuronClusterConnectionBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Связь кластеров нейронов
 * 
 * @property array|Neuronable[] $clusters Массив связанных кластеров нейронов
 * @property boolean $status Статус
 *
 * @method static NeuronClusterConnectionBuilder query()
 */
class NeuronClusterConnection extends Model
{
    protected $table = 'neuron_cluster_connections';

    protected $casts = [
        'clusters' => 'array',
        'status' => 'boolean',
    ];
    
    public function newEloquentBuilder($query): NeuronClusterConnectionBuilder
    {
        return new NeuronClusterConnectionBuilder($query);
    }
}