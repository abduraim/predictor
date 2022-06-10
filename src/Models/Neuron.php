<?php

namespace Abduraim\Predictor\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $neuronable Сущность
 */
class Neuron extends Model
{
    protected $table = 'neurons';

    protected $fillable = ['options'];

    protected $casts = ['options' => 'array'];

    public function neuronable()
    {
        return $this->morphTo();
    }
}