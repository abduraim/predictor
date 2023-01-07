<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Нейрон
 *
 * @property int $id ID
 * @property string $neuronable_type Тип сущности
 * @property int $neuronable_id ID сущности
 * @property array $options Опции
 * @property Carbon $created_at Timestamp создания
 * @property Carbon $updated_at Timestamp обновления
 *
 * @property Model $neuronable Сущность
 * 
 * @method static NeuronBuilder query()
 */
class Neuron extends Model
{
    protected $table = 'neurons';

    protected $fillable = ['options'];

    protected $casts = ['options' => 'array'];

    public function newEloquentBuilder($query): NeuronBuilder
    {
        return new NeuronBuilder($query);
    }

    public function neuronable()
    {
        return $this->morphTo();
    }
}