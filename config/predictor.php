<?php

return [
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
        ],
    ],
    'path' => 'predictor',
    'middleware' => 'web',
];