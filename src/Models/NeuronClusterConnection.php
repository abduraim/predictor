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
 * @property int $determinant_cluster_id ID Кластера нейронов определителя
 * @property int $target_cluster_id ID Кластера нейронов определяемого
 * @property bool $status Статус
 * @property int $weight Вес связи
 * @property Carbon $created_at Timestamp создания
 * @property Carbon $updated_at Timestamp обновления
 *
 * @property NeuronCluster $determinantNeuronCluster Кластер нейронов определитель
 * @property NeuronCluster $targetNeuronCluster Кластер нейронов определяемый
 * @property NeuronConnection[] $neuron_connections Связи нейронов
 * 
 * @method static NeuronClusterConnectionBuilder query()
 */
class NeuronClusterConnection extends Model
{
//    use Filterable;

    protected $table = 'neuron_cluster_connections';

    protected $fillable = [
        'determinant_cluster_id',
        'target_cluster_id',
        'status',
        'weight',
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
    public function newEloquentBuilder($query): NeuronClusterConnectionBuilder
    {
        return new NeuronClusterConnectionBuilder($query);
    }

    public function determinantNeuronCluster()
    {
        return $this->belongsTo(NeuronCluster::class, 'determinant_cluster_id', 'id');
    }

    public function targetNeuronCluster()
    {
        return $this->belongsTo(NeuronCluster::class, 'target_cluster_id', 'id');
    }

    public function neuron_connections()
    {
        return $this->hasMany(NeuronConnection::class, 'neuron_cluster_connection_id');
    }
    
}