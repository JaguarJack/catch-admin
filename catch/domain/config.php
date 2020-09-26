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

            'access_key' => 'LTAI4G2JF2iiJEfnQYm4vhvr',

            'access_secret' => 'YDe2sff7uDN1nRPdfvVAFCW6lLaOrC',
        ],

        /**
         * 腾讯云配置
         *
         */
        'qcloud' => [
            'access_key' => '',
            'access_secret' => '',
        ]
    ]
];