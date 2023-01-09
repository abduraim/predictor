<?php

namespace Abduraim\Predictor\Exceptions\NeuronClusterConnection;

class NeuronClusterConnectionDuplicateException extends \Exception
{
    protected $message = 'Попытка создать уже существующую связь кластеров';
}