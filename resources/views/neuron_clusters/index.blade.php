@php
    /**
     * @var \Abduraim\Predictor\Models\NeuronCluster $neuron_cluster
     */
@endphp

@extends('predictor::layouts.main', ['title' => $title])

@section('content')

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Класс/Алиас</th>
            <th>Название</th>
        </tr>
        </thead>
        <tbody>
        @foreach($neuron_clusters as $neuron_cluster)
            <tr>
                <td>{{ $neuron_cluster->id }}</td>
                <td><a href="{{ route('predictor.neuron_clusters.edit', $neuron_cluster) }}">{{ $neuron_cluster->neuronable_type }}</a></td>
                <td>{{ $neuron_cluster->title }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection