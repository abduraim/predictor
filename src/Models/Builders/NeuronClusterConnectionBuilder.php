<?php

namespace Abduraim\Predictor\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class NeuronClusterConnectionBuilder extends Builder
{
    /**
     * Scope by cluster contains
     * 
     * @param string $clusterType
     * @return NeuronClusterConnectionBuilder
     */
    public function whereHasCluster(string $clusterType): self
    {
        return $this->whereJsonContains('clusters', $clusterType);
    }
}