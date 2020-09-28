<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    'domains' => [
        // 默认阿里云
        'default' => 'aliyun',

        /**
         * 阿里云配置
         *
         */
        'aliyun' => [
            'api_domain' => 'http://alidns.aliyuncs.com',

            'access_key' => Env::get('aliyun.access_key', ''),

            'access_secret' =>  Env::get('aliyun.access_secret', ''),
        ],

        /**
         * 腾讯云配置
         *
         */
        'qcloud' => [
            'api_domain' => 'cns.api.qcloud.com',

            'access_key' => Env::get('qcloud.access_key', ''),

            'access_secret' => Env::get('qcloud.access_secret', ''),
        ]
    ]
];