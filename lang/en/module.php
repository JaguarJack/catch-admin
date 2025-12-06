<?php
declare(strict_types=1);

return [
    'create' => 'Create Module',
    'update' => 'Update Module',
    'form' => [
        'name' => [
            'title' => 'Module Name',
            'required' => 'module name required',
        ],
        'path' => [
            'title' => 'Module Path',
            'required' => 'module Path required',
        ],
        'desc' => [
            'title' => 'Description',
        ],
        'keywords' => [
            'title' => 'Keywords',
        ],
        'dirs' => [
            'title' => 'Default Dirs',
            'Controller' => 'Controller',
            'Model' => 'Model',
            'Database' => 'Database',
            'Request' => 'Request',
        ],
    ],
];
