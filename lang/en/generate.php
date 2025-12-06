<?php
declare(strict_types=1);

return [
    'schema' => [
        'title' => 'Create Schema',
        'name' => 'Schema Name',
        'name_verify' => 'please input schema name',
        'engine' => [
            'name' => 'Search Engine',
            'verify' => 'please select schema engine',
            'placeholder' => 'select schema engine',
        ],
        'default_field' => [
            'name' => 'Default Field',
            'created_at' => 'Create time',
            'updated_at' => 'Update Time',
            'creator' => 'Creator',
            'delete_at' => 'SoftDelete',
        ],
        'comment' => [
            'name' => 'Schema Comment',
            'verify' => 'please input schema comment',
        ],
        'structure' => [
            'title' => 'Create Schema Structure',
            'field_name' => [
                'name' => 'Field Name',
                'verify' => 'please input field name',
            ],
            'length' => 'Length',
            'type' => [
                'name' => 'Field Type',
                'placeholder' => 'select field type',
                'verify' => 'please select field type',
            ],
            'form_label' => 'Form Label',
            'form_component' => 'Component',
            'list' => 'List',
            'render' => 'Render Where',
            'form' => 'Form',
            'export' => 'Export',
            'import' => 'Import',
            'unique' => 'Unique',
            'search' => 'Search',
            'search_op' => [
                'name' => 'Search Operate',
                'placeholder' => 'select search operate',
            ],
            'nullable' => 'Nullable',
            'default' => 'Default',
            'rules' => [
                'name' => 'Verify Rules',
                'placeholder' => 'select verify rules',
            ],
            'operate' => 'Operate',
            'comment' => 'Field Comment',
        ],
    ],
    'code' => [
        'title' => 'Code Gen',
        'module' => [
            'name' => 'module',
            'placeholder' => 'please select module',
            'verify' => 'please select module first',
        ],
        'controller' => [
            'name' => 'Controller',
            'placeholder' => 'please input controller name',
            'verify' => 'please input Controller name  first',
        ],
        'model' => [
            'name' => 'Model',
            'placeholder' => 'please input model name',
            'verify' => 'please input model name  first',
        ],
        'paginate' => [
            'name' => 'Paginate',
            'placeholder' => 'Control List whether to use pagination',
        ],
        'gen' => [
            'form' => 'Generate Form',
            'dymaic' => 'Dynamic Form',
            'dialogForm' => 'Dialog Form',
        ],
        'menu' => [
            'name' => 'Menu Name',
            'placeholder' => 'Please input menu name. If not filled in, it will not be generated',
            'verify' => 'Please input menu name  first',
        ],

        'operation' => [
            'name' => 'Operation',
            'import' => 'Import Data',
            'export' => 'Export Data',
        ],
    ],
];
