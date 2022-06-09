<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Interfaces\PredictorInterface;

class PredictorService implements PredictorInterface
{

    public function predict()
    {
        return ['qwer', 'asdf'];
    }
}