<?php

namespace Abduraim\Predictor\Http\Requests\NeuronClusterConnections;

use Abduraim\Predictor\Models\NeuronCluster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Запрос на создание новой связи кластеров нейронов
 *
 * @property-read int $determinant_cluster_id ID определяющего кластера нейрона
 * @property-read int $target_cluster_id ID определяемого кластера нейрона
 * @property-read int $status Статус
 * @property-read int $weight Вес
 */
class StoreRequest extends UpdateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge(['status' => $this->has('status')]);

        if (!$this->weight) {
            $this->merge(['weight' => 1]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return parent::rules() + [
            'determinant_cluster_id' => ['required', 'integer', 'different:target_cluster_id', Rule::exists(NeuronCluster::class, 'id')],
            'target_cluster_id' => ['required', 'integer', 'different:determinant_cluster_id', Rule::exists(NeuronCluster::class, 'id')],
        ];
    }
}
