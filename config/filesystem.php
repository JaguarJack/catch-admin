<?php

use think\facade\Env;

return [
    // 默认磁盘
    'default' => Env::get('filesystem.driver', 'local'),
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' =>  app()->getRootPath() . 'public'.DIRECTORY_SEPARATOR.'images',
            'domain' => env('app.domain'),
        ],
        'public' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/storage',
            // 磁盘路径对应的外部URL路径
            'url'        => '/storage',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
        'qiniu' => [
            'type'       => 'qiniu',
            'access_key' => '',
            'secret_key' => '',
            'bucket'     => '',
            'domain'     => '',
        ],
        'oss' => [
            'type'   => 'oss',
            'prefix' => '',
            'access_key' => '',
            'secret_key' => '',
            'end_point'  => '', // ssl：https://iidestiny.com
            'bucket'     => '',
            'is_cname'   =>  false
        ],
        // 腾讯云配置
        'qcloud' => [
            'type'        => 'qcloud',
            'region'      => '',
            'credentials' => [
                'appId'      => '', // 域名中数字部分
                'secretId'   => '',
                'secretKey'  => '',
            ],
            'bucket'          => '',
            'timeout'         => 60,
            'connect_timeout' => 60,
            'cdn'             => '',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
        ]
    ]

];
