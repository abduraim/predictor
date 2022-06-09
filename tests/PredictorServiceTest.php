<?php

namespace Tests;

use Abduraim\Predictor\PredictorService;
use PHPUnit\Framework\TestCase;

class PredictorServiceTest extends TestCase
{
    public function testValue()
    {
        $predictorService = new PredictorService();
        $value = $predictorService->predict();

        $this->assertIsArray($value);
    }
}