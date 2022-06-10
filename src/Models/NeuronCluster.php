<?php

namespace Abduraim\Predictor\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $neuronable_type Класс модели
 * @property string $title Заголовок
 */
class NeuronCluster extends Model
{
    protected $table = 'neuron_clusters';

    public function modelClass()
    {
        return $this->neuronable_type;
    }
}