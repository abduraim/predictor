<?php

namespace Abduraim\Predictor\Http\Controllers;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view(
            'predictor::dashboard',
            [
                'title' => 'Dashboard'
            ]
        );
    }
}