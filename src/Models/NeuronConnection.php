<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronConnectionBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $neuronable Сущность
 *
 * @property array|int[] $neurons Массив id'шек нейронов
 * @property bool $status Статус
 * @property int $weight Вес
 * 
 * @method static NeuronConnectionBuilder query()
 *
 */
class NeuronConnection extends Model
{
    protected $table = 'neuron_connections';

    protected $fillable = [
        'neuron_cluster_connection_id',
        'neurons',
        'status',
        'weight',
        'updated_at',
    ];

    protected $casts = [
        'neurons' => 'array',
        'status' => 'boolean',
    ];
    
    public function newEloquentBuilder($query): NeuronConnectionBuilder
    {
        return new NeuronConnectionBuilder($query);
    }

    /**
     * Возвращает Id связанного нейрона
     *
     * @param string $neuronId Исходный нейрон
     * @return string
     */
    public function getOppositeNeuronId(string $neuronId): string
    {
        return collect($this->neurons)
            ->filter(function ($item) use ($neuronId) {
                return $item !== $neuronId;
            })
            ->first();
    }

    /**
     * Включенные связи
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeEnabled(Builder $builder)
    {
        return $builder->where('status', true);
    }

    /**
     * Проверенные связи
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeChecked(Builder $builder)
    {
        return $builder->whereNotNull('updated_at');
    }

    /**
     * Рабочие связи (включенные и проверенные)
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActual(Builder $builder)
    {
        return $this->enabled()->checked();
    }
}