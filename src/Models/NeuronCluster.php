<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronClusterBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Кластер нейронов
 * 
 * @property string $neuronable_type Класс модели
 * @property string $title Заголовок
 *
 * @property Neuron[]|Collection $neurons Нейроны кластера
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
    
    public function newEloquentBuilder($query): NeuronClusterBuilder
    {
        return new NeuronClusterBuilder($query);
    }
}