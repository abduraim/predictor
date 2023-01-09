<?php

namespace Abduraim\Predictor\Http\Controllers;

use Abduraim\Predictor\PredictorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ToolsController extends Controller
{
    public function sync(PredictorService $predictorService, Request $request)
    {
        $predictorService->sync();

        $request->session()->flash('message', 'Успешно синхронизировано!');
        
        return redirect()->back();
    }
}