<?php

namespace Abduraim\Predictor\Exceptions\NeuronClusterConnection;

class NeuronClusterConnectionDuplicateException extends \Exception
{
    protected $message = 'Такая связь кластеров нейронов уже существует';
}