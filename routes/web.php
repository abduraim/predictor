<?php

use Abduraim\Predictor\Http\Controllers;
use Illuminate\Support\Facades\Route;


Route::name('predictor.')->group(function () {

    Route::get('dashboard', [Controllers\DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::resource('neuron_clusters', Controllers\NeuronClusterController::class)
        ->except(['create', 'store']);
    
    Route::resource('neuron_cluster_connections', Controllers\NeuronClusterConnectionController::class);

    Route::prefix('tools')->as('tools.')->group(function () {
        Route::get('sync', [Controllers\ToolsController::class, 'sync'])
            ->name('sync');
    });


});
