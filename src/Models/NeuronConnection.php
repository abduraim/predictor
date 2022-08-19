<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronConnectionBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $neuronable Сущность
 *
 * @property array|int[] $neurons Массив id'шек нейронов
 * @property bool $status Статус
 * @property int $weight Вес
 * 
 * @method static NeuronConnectionBuilder query()
 */
class NeuronConnection extends Model
{
    protected $table = 'neuron_connections';

    protected $fillable = [
        'neurons',
        'status',
        'weight',
    ];

    protected $casts = [
        'neurons' => 'array',
        'status' => 'boolean',
    ];
    
    public function newEloquentBuilder($query): NeuronConnectionBuilder
    {
        return new NeuronConnectionBuilder($query);
    }

    
    public static function boot()
    {
        parent::boot();

        static::creating(function (NeuronConnection $neuronConnection) {
            if (NeuronConnection::query()->whereNeuronIds($neuronConnection->neurons)->exists()) {
                throw new \Exception('NeuronConnection already exists!');
            }
        });
    }
}