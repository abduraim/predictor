<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Predictor</title>

    <link href="{{ asset(mix('app.css', 'vendor/predictor')) }}" rel="stylesheet">
</head>
<body>

@if(Session::has('message'))
    <div class="alert alert-success"> {{ Session::get('message') }}</div>
@endif

@if ($errors->any())
    <h3>Errors:</h3>
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<header>
    <ul>
        <li><a href="{{ route('predictor.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('predictor.neuron_clusters.index') }}">Кластеры нейронов</a></li>
        <li><a href="{{ route('predictor.neuron_cluster_connections.index') }}">Связи кластеров нейронов</a></li>
    </ul>
</header>

<h1>{{ $title ?? '' }}</h1>

@yield('content')

</body>
</html>