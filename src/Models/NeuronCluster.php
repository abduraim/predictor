<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronClusterBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $neuronable_type Класс модели
 * @property string $title Заголовок
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

    public function modelClass()
    {
        return $this->neuronable_type;
    }
    
    public function newEloquentBuilder($query): NeuronClusterBuilder
    {
        return new NeuronClusterBuilder($query);
    }
}