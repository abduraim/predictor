<?php

namespace Abduraim\Predictor\Models\Collections;

use Abduraim\Predictor\Interfaces\Neuronable;

class NeuronableCollection extends \Illuminate\Database\Eloquent\Collection
{

    public function getNeuronIds(): array
    {
        return $this->map(function (Neuronable $neuronable) {
            return $neuronable->neuron->getKey();
        })->toArray();
    }

}