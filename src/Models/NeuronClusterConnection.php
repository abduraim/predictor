<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Interfaces\Neuronable;
use Illuminate\Database\Eloquent\Model;

/**
 * Связь кластеров нейронов
 * 
 * @property array|Neuronable[] $clusters Массив связанных кластеров нейронов
 * @property boolean $status Статус
 */
class NeuronClusterConnection extends Model
{
    protected $table = 'neuron_cluster_connections';

    protected $casts = [
        'clusters' => 'array',
        'status' => 'boolean',
    ];
}