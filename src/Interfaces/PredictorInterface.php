<?php

namespace Abduraim\Predictor\Interfaces;

use Illuminate\Support\Collection;

interface PredictorInterface
{
    /**
     * Предсказать
     * @return mixed
     */
    public function predict();

    /**
     * Collection of Neuronable model names
     * @return Collection
     */
    public function getNeuronableModels(): Collection;

    /**
     * Sync Neuronable Models with their Clusters
     * @return void
     */
    public function syncNeuronableModels(): void;

    /**
     * Sync neurons with their models
     * @return void
     */
    public function syncNeurons(): void;

    /**
     * Sync Neuron connections by Cluster connections
     * @return void
     */
    public function syncNeuronConnections(): void;
}