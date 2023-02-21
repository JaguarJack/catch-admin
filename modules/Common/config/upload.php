<?php
return [
    // 文件最大
    'max_size' => 10 * 1024 * 1024,

    // oss 配置
    'oss' => [
        'bucket' => env('ALIOSS_BUCKET'),

        'access_id' => env('ALIOSS_ACCESS_ID'),

        'access_secret' => env('ALIOSS_ACCESS_SECRET'),

        'endpoint' => env('ALIOSS_ENDPOINT'),

        'dir' => env('ALIOSS_UPLOAD_DIR')
    ],
];
