<?php

namespace Abduraim\Predictor\Exceptions\NeuronClusterConnection;

class NeuronClusterConnectionIdenticalClustersException extends \Exception
{
    protected $message = 'Попытка создать связь кластеров из одного и того же кластера';
}