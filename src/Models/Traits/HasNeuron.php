<?php

namespace Abduraim\Predictor\Models\Traits;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Neuron;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasNeuron
{
    /**
     * Neuron
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function neuron()
    {
        return $this->morphOne(Neuron::class, 'neuronable');
    }

    /**
     * Observers
     *
     * @return void
     */
    public static function bootHasNeuron()
    {
        static::created(function(Neuronable $model) {
            $model->neuron()->create(['options' => ['fdsa', 'rewq', 'vcxz']]);
        });
    }
}