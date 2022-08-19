<?php

namespace Abduraim\Predictor\Interfaces;

use Illuminate\Support\Collection;

interface PredictorInterface
{
    /**
     * Предсказать
     * 
     * @return mixed
     */
    public function predict();

    /**
     * Синхронизация нейронных кластеров, нейронов и их связей в приложении и БД
     * 
     * @return void
     */
    public function sync(): void;
}