@php
    /**
    * @var \Abduraim\Predictor\Models\NeuronCluster $neuron_cluster
    */
@endphp

@extends('predictor::layouts.main')

@section('content')

    <a href="{{ route('predictor.neuron_cluster_connections.index') }}">Назад</a>

    <hr>

    <form action="{{ route('predictor.neuron_cluster_connections.store') }}" method="post">
        @csrf

        <label for="determinant_cluster_id">Определяющий кластер нейронов:</label>
        <select name="determinant_cluster_id" id="determinant_cluster_id">
            <option value="">Выбрать...</option>
            @foreach($neuron_clusters as $neuron_cluster)
                <option value="{{ $neuron_cluster->id }}" @selected(old('determinant_cluster_id') == $neuron_cluster->id)>
                    {{ $neuron_cluster->title }}
                </option>
            @endforeach
        </select>

        <label for="target_cluster_id">Определяемый кластер нейронов:</label>
        <select name="target_cluster_id" id="target_cluster_id">
            <option value="">Выбрать...</option>
            @foreach($neuron_clusters as $neuron_cluster)
                <option value="{{ $neuron_cluster->id }}" @selected(old('target_cluster_id') == $neuron_cluster->id)>
                    {{ $neuron_cluster->title }}
                </option>
            @endforeach
        </select>

        <label for="status">Статус:</label>
        <input type="checkbox" name="status" id="status" {{ old('status') ? 'checked="checked"' : '' }}>

        <label for="weight">Вес связи:</label>
        <input type="text" name="weight" id="weight" value="{{ old('weight') }}">

        <button>Создать</button>
    </form>

@endsection