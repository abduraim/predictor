<?php

namespace Abduraim\Predictor\Http\Controllers;

use Abduraim\Predictor\Http\Requests\NeuronClusterConnections\StoreRequest;
use Abduraim\Predictor\Http\Requests\NeuronClusterConnections\UpdateRequest;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Repositories\NeuronClusterConnectionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NeuronClusterConnectionController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'Связи кластеров нейронов');
    }

    public function index()
    {
        $neuronClusterConnections = NeuronClusterConnection::query()
            ->with(['determinantNeuronCluster', 'targetNeuronCluster'])
            ->get();

        return view(
            'predictor::neuron_cluster_connections.index',
            [
                'neuron_cluster_connections' => $neuronClusterConnections
            ]
        );
    }

    public function create()
    {
        $neuronClusters = NeuronCluster::query()->get();

        return view(
            'predictor::neuron_cluster_connections.create',
            [
                'neuron_clusters' => $neuronClusters
            ]
        );
    }

    public function store(StoreRequest $request, NeuronClusterConnectionRepository $repository)
    {
        $determinantCluster = NeuronCluster::find($request->determinant_cluster_id);
        $targetCluster = NeuronCluster::find($request->target_cluster_id);
        
        $neuronClusterConnection = $repository
            ->store(
                $determinantCluster,
                $targetCluster,
                $request->status,
                $request->weight
            );

        $request->session()->flush('message', 'Успешно создано!');

        return redirect()->route('predictor.neuron_cluster_connections.edit', $neuronClusterConnection);
    }

    public function edit(NeuronClusterConnection $neuronClusterConnection)
    {
        $neuronClusterConnection->load(['determinantNeuronCluster', 'targetNeuronCluster']);

        return view(
            'predictor::neuron_cluster_connections.edit',
            [
                'neuron_cluster_connection' => $neuronClusterConnection,
            ]
        );
    }

    public function update(NeuronClusterConnection $neuronClusterConnection, UpdateRequest $request)
    {
        $neuronClusterConnection->update($request->validated());

        $request->session()->flash('message', 'Успешно обновлено!');

        return redirect()->route('predictor.neuron_cluster_connections.index');
    }

    public function destroy(NeuronClusterConnection $neuronClusterConnection, Request $request)
    {
        $neuronClusterConnection->delete();

        $request->session()->flash('message', 'Успешно удалено!');

        return redirect()->route('predictor.neuron_cluster_connections.index');
    }
}