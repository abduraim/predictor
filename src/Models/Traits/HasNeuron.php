<?php

namespace Abduraim\Predictor\Models\Traits;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronConnection;
use Abduraim\Predictor\Repositories\NeuronRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 *
 * @property-read Neuron|null $neuron Нейрон
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
    
    public function newCollection(array $models = [])
    {
        return new NeuronableCollection($models);
    }

    /**
     * Observers
     *
     * @return void
     */
    public static function bootHasNeuron()
    {
        static::created(function(Neuronable $model) {
            (new NeuronRepository())->store($model);
        });
    }
}