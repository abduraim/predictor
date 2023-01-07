<?php

namespace Abduraim\Predictor\Interfaces;

use Illuminate\Support\Collection;

interface PredictorInterface
{
    /**
     * Предсказать
     *
     * @param int $predictableNeuronClusterId Предсказываемый кластер нейронов
     * @param array $payload Выбранные варианты
     * @return mixed
     */
    public function predict(int $predictableNeuronClusterId, array $payload);

    /**
     * Синхронизация нейронных кластеров, нейронов и их связей в приложении и БД
     * 
     * @return void
     */
    public function sync(): void;
}