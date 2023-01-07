<?php

namespace Abduraim\Predictor\Interfaces;

use Abduraim\Predictor\Models\Neuron;

/**
 * @property Neuron $neuron Нейрон
 */
interface Neuronable
{
    /**
     * Нейрон модели
     */
    public function neuron();
}