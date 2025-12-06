<?php
declare(strict_types=1);

return [
    'create' => '创建模块',
    'update' => '更新模块',
    'form' => [
        'name' => [
            'title' => '模块名称',
            'required' => '请输入模块名称',
        ],
        'path' => [
            'title' => '模块目录',
            'required' => '请输入模块目录',
        ],
        'desc' => [
            'title' => '模块描述',
        ],
        'keywords' => [
            'title' => '模块关键字',
        ],
        'dirs' => [
            'title' => '默认目录',
            'Controller' => 'Controller 目录',
            'Model' => 'Model 目录',
            'Database' => 'Database 目录',
            'Request' => 'Request 目录',
        ],
    ],
];
