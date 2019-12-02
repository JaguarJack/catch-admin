<?php
// 事件定义文件
return [
    'bind'      => [
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'RouteLoaded' => [
            \app\event\LoadModuleRoutes::class,
        ],
    ],

    'subscribe' => [
    ],
];
