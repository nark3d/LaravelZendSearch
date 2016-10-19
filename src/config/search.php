<?php

return [
    'index' => [
        'path' => storage_path('app').'/laravelzendsearch/index'
    ],
    'modelPath' => app_path(),
    'language' => 'en',
    'filters' => [
        'StopWords' => 'en',
        'ShortWords' => true
    ]
];
