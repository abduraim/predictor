@php
    /**
     * @var \Abduraim\Predictor\Models\NeuronClusterConnection $neuron_cluster_connection
     */
@endphp

@extends('predictor::layouts.main', ['title' => $title])

@section('content')

    <a href="{{ route('predictor.neuron_cluster_connections.create') }}">Создать связь кластеров нейронов</a>

    <hr>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Отношение кластеров (определяющий -> определяемый)</th>
            <th>Статус</th>
            <th>Вес связи</th>
        </tr>
        </thead>
        <tbody>
        @foreach($neuron_cluster_connections as $neuron_cluster_connection)
            <tr>
                <td>{{ $neuron_cluster_connection->id }}</td>
                <td>
                    <a href="{{ route('predictor.neuron_cluster_connections.edit', $neuron_cluster_connection) }}">
                        {{ $neuron_cluster_connection->determinantNeuronCluster->title }} -> {{ $neuron_cluster_connection->targetNeuronCluster->title }}
                    </a>
                </td>
                <td>{{ $neuron_cluster_connection->status }}</td>
                <td>{{ $neuron_cluster_connection->weight }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection