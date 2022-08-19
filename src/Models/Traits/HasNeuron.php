<?php

namespace Abduraim\Predictor\Models\Traits;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronConnection;
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
            $model->neuron()->create(['options' => []]);
        });

        static::deleted(function (Neuronable $model) {
            $neuron = $model->neuron;

            // Remove neuron connections
            NeuronConnection::query()->whereHasNeuron($neuron)->delete();

            // Remove neuron
            $neuron->delete();
        });
    }
}