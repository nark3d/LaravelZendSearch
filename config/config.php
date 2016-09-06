<?php
return [
    /*
     |--------------------------------------------------------------------------
     | The configurations of search index.
     |--------------------------------------------------------------------------
     |
     | The "path" is the path to Lucene search index.
     |
     | The "models" is the list of the descriptions for models. Each description
     | must contains class of model and fields available for search indexing.
     |
     | For example, model's description can be like this:
     |
     |      'namespace\ModelClass' => [
     |          'fields' => [
     |              'name', 'description', // Fields for indexing.
     |          ]
     |      ]
     |
     */
    'index' => [
        'path' => storage_path('app') . '/best-served-cold-zend-search/index',
        'models' => [
            // Add models descriptions here.
        ],
    ]
];
