<?php

use think\facade\Env;

return [
    // 默认磁盘
    'default' => Env::get('filesystem.driver', 'local'),
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
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
            'access_key' => 'iLOE193LsBtlY4LZSN6cMIec9FTmMDRXPoFnBAec',
            'secret_key' => '2w7bQ6dw_HA2mfZHxXyzROxXach_9-6kavgJ9aHl',
            'bucket'     => 'assets',
            'domain'     => 'assets.njphper.com',
        ],
        'oss' => [
            'type'   => 'oss',
            'prefix' => '',
            'access_key' => 'XbBP7l7B8ZrXOkDk',
            'secret_key' => 'EhNxzuIMZJsvpjWXSyvsSgcCML5In2',
            'end_point'  => 'https://oss-cn-beijing.aliyuncs.com', // ssl：https://iidestiny.com
            'bucket'     => 'jaguarjack-test',
            'is_cname'   =>  false
        ],
        // 腾讯云配置
        'qcloud' => [
            'type'        => 'qcloud',
            'region'      => 'ap-chengdu',
            'credentials' => [
                'appId'      => '1252879105', // 域名中数字部分
                'secretId'   => 'AKIDVpqhjprOp0BNL2EhhRurb7QZjspWmoiN',
                'secretKey'  => 'ojV3OuEKCohjWq0yhBPZWEoFTTYN5c5w',
            ],
            'bucket'          => 'jaguarjack-1252879105',
            'timeout'         => 60,
            'connect_timeout' => 60,
            'cdn'             => '',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
        ]
    ]

];
