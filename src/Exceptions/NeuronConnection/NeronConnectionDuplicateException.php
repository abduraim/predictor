<?php

namespace Abduraim\Predictor\Exceptions\NeuronConnection;

class NeronConnectionDuplicateException extends \Exception
{
    protected $message = 'Такая связь нейронов уже существует';
}