@php
    /**
     * @var \Abduraim\Predictor\Models\NeuronClusterConnection $neuron_cluster_connection
     */
@endphp

@extends('predictor::layouts.main')

@section('content')

    <a href="{{ route('predictor.neuron_cluster_connections.index') }}">Назад</a>

    <hr>

    <form action="{{ route('predictor.neuron_cluster_connections.update', $neuron_cluster_connection) }}" method="post">
        @method('put')
        @csrf

        <h3>{{ $neuron_cluster_connection->determinantNeuronCluster->title }} -> {{ $neuron_cluster_connection->targetNeuronCluster->title }}</h3>

        <label for="status">Статус:</label>
        <input type="checkbox" name="status" id="status" {{ $neuron_cluster_connection->status ? 'checked="checked"' : '' }}>

        <label for="weight">Вес связи:</label>
        <input type="text" name="weight" id="weight" value="{{ $neuron_cluster_connection->weight }}">

        <button>Обновить</button>
    </form>

    <hr>

    <form action="{{ route('predictor.neuron_cluster_connections.destroy', $neuron_cluster_connection) }}" method="post">
        @method('delete')
        @csrf
        <button>Удалить</button>
    </form>

@endsection