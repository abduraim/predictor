<?php

namespace Abduraim\Predictor\Models;

use Abduraim\Predictor\Models\Builders\NeuronConnectionBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Model $neuronable Сущность
 *
 * @property int $determinant_neuron_id Id нейроноа определителя
 * @property int $target_neuron_id Id нейроноа определяемого
 * @property bool $status Статус
 * @property int $weight Вес
 *
 * @property NeuronClusterConnection $neuronClusterConnection Связь кластеров нейронов
 * @property Neuron $determinantNeuron Нейрон определитель
 * @property Neuron $targetNeuron Нейрон определяемый
 *
 * @method static NeuronConnectionBuilder query()
 *
 */
class NeuronConnection extends Model
{
    protected $table = 'neuron_connections';

    protected $fillable = [
        'neuron_cluster_connection_id',
        'determinant_neuron_id',
        'target_neuron_id',
        'status',
        'weight',
        'updated_at',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    
    public function newEloquentBuilder($query): NeuronConnectionBuilder
    {
        return new NeuronConnectionBuilder($query);
    }

    /**
     * Связь кластеров нейронов данной связи нейронов
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function neuronClusterConnection(): BelongsTo
    {
        return $this->belongsTo(NeuronClusterConnection::class,'neuron_cluster_connection_id', 'id');
    }

    /**
     * Нейрон определитель
     *
     * @return BelongsTo
     */
    public function determinantNeuron()
    {
        return $this->belongsTo(Neuron::class, 'determinant_neuron_id', 'id');
    }

    /**
     * Нейрон определяемый
     *
     * @return BelongsTo
     */
    public function targetNeuron()
    {
        return $this->belongsTo(Neuron::class, 'target_neuron_id', 'id');
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