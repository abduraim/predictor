<?php

namespace Abduraim\Predictor\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $neuronable Сущность
 */
class Neuron extends Model
{
    protected $table = 'neuron_connections';
}