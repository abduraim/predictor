<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $neuronable Сущность
 * 
 * @method static NeuronBuilder query()
 */
class Neuron extends Model
{
    protected $table = 'neurons';

    protected $fillable = ['options'];

    protected $casts = ['options' => 'array'];

    public function newEloquentBuilder($query): NeuronBuilder
    {
        return new NeuronBuilder($query);
    }

    public function neuronable()
    {
        return $this->morphTo();
    }
}