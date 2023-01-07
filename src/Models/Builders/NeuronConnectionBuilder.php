<?php

namespace Abduraim\Predictor\Models\Builders;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Models\Neuron;
use Illuminate\Database\Eloquent\Builder;

/**
 * Builder связей неронов
 *
 * @method static NeuronConnectionBuilder enabled() Включенные связи
 * @method static NeuronConnectionBuilder checked() Проверенные связи
 * @method static NeuronConnectionBuilder actual() Рабочие связи (включенные и проверенные)
 *
 */
class NeuronConnectionBuilder extends Builder
{
    /**
     * Scope by contains Neuron
     *
     * @param Neuron $neuron
     * @return $this
     */
    public function whereHasNeuron(Neuron $neuron): self
    {
        return $this->whereJsonContains('neurons', $neuron->getKey());
    }

    /**
     * Точное совпадение содержащихся нейронов
     * 
     * @param int ...$neurons
     * @return $this
     */
    public function whereNeuronIds(array $neuronIds): self
    {
        return $this
            ->whereJsonLength('neurons', '=', count($neuronIds))
            ->whereJsonContains('neurons', $neuronIds);
    }


}