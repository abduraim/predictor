<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronClusterBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Кластер нейронов
 *
 * @property int $id ID
 * @property string $neuronable_type Класс модели
 * @property string $title Заголовок
 * @property Carbon $created_at Timestamp создания
 * @property Carbon $updated_at Timestamp обновления
 *
 * @property Neuron[]|Collection $neurons Нейроны кластера
 * @property NeuronCluster[]|Collection $determinatableNeuronClusterConnections Связи кластеров нейронов, в которых данный является определителем
 * @property NeuronCluster[]|Collection $targetableNeuronClusterConnections Связи кластеров нейронов, в которых данный является определяемым
 *
 * @method static NeuronClusterBuilder query()
 */
class NeuronCluster extends Model
{
    protected $table = 'neuron_clusters';

    protected $fillable = [
        'neuronable_type',
        'title',
    ];

    /**
     * Нейроны кластера
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function neurons(): HasMany
    {
        return $this->hasMany(Neuron::class, 'neuronable_type', 'neuronable_type');
    }

    public function determinatableNeuronClusterConnections()
    {
        return $this->hasMany(NeuronClusterConnection::class, 'determinant_cluster_id', 'id');
    }

    public function targetableNeuronClusterConnections()
    {
        return $this->hasMany(NeuronClusterConnection::class, 'target_cluster_id');
    }
    
    public function newEloquentBuilder($query): NeuronClusterBuilder
    {
        return new NeuronClusterBuilder($query);
    }
}