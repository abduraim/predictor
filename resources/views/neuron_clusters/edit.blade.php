@php
    /**
     * @var \Abduraim\Predictor\Models\NeuronCluster $neuron_cluster
     */
@endphp

@extends('predictor::layouts.main')

@section('content')

    <a href="{{ route('predictor.neuron_clusters.index') }}">Назад</a>

    <hr>

    <form action="{{ route('predictor.neuron_clusters.update', $neuron_cluster) }}" method="post">
        @method('put')
        @csrf
        <input type="text" name="title" value="{{ $neuron_cluster->title }}">
        <button>Обновить</button>
    </form>

    <hr>

    <form action="{{ route('predictor.neuron_clusters.destroy', $neuron_cluster) }}" method="post">
        @method('delete')
        @csrf
        <button>Удалить</button>
    </form>

@endsection