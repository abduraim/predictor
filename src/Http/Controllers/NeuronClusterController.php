<?php

namespace Abduraim\Predictor\Http\Controllers;

use Abduraim\Predictor\Models\NeuronCluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NeuronClusterController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'Кластеры нейронов');
    }

    public function index(Request $request)
    {
        $neuronClusters = NeuronCluster::query()
//            ->filter($request->all())
            ->get();

        return view(
            'predictor::neuron_clusters.index',
            [
                'neuron_clusters' => $neuronClusters
            ]
        );
    }

    public function edit(NeuronCluster $neuronCluster)
    {
        return view(
            'predictor::neuron_clusters.edit',
            [
                'neuron_cluster' => $neuronCluster
            ]
        );
    }

    public function update(NeuronCluster $neuronCluster, Request $request)
    {
        $neuronCluster->title = $request->title;
        $neuronCluster->save();

        $request->session()->flash('message', 'Успешно обновлено!');

        return redirect()->route('predictor.neuron_clusters.index');
    }

    public function destroy(NeuronCluster $neuronCluster, Request $request)
    {
        $neuronCluster->delete();

        $request->session()->flash('message', 'Успешно удалено!');

        return redirect()->route('predictor.neuron_clusters.index');
    }
}