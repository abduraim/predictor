<?php

namespace Abduraim\Predictor\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class NeuronClusterBuilder extends Builder
{
    /**
     * Scope by neuronable_type
     * 
     * @param string $neuronableType
     * @return $this
     */
    public function whereNeuronableType(string $neuronableType): self
    {
        return $this->where('neuronable_type', $neuronableType);
    }
}