<?php
return [
    // 忽略菜单
    'ignore' => [
        'route' => [
            'index:index:index', // 首页
            'index:index:theme', // 主题选择
        ],
        // 模块
        'module' => [
            'login',
        ],
    ],

    // 错误提示
    'error' => root_path('catchAdmin/index/view/')  . 'error.html',
];
