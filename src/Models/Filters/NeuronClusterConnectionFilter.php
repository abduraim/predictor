<?php

namespace Abduraim\Predictor\Models\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Arr;

/**
 * Фильтры связей кластеров нейронов
 */
class NeuronClusterConnectionFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * По ID
     *
     * @param int|array $ids
     * @return NeuronClusterConnectionFilter
     */
    public function id(int|array $ids): NeuronClusterConnectionFilter
    {
        return $this->whereIn('id', Arr::wrap($ids));
    }
}
