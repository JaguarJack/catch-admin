<?php

return [
        /**
        * set domain if you need
        *
        */
        'domain' => '',

        /**
        * 权限配置
        *
        */
        'permissions' => [
        /**
        * get 请求不验证
        */
        'is_allow_get' => true,

        /**
        * 超级管理员 ID
        *
        */
        'super_admin_id' => 1,

        /**
         * 方法认证标记
         *
         * 尽量使用唯以字符
         *
         */
        'method_auth_mark' => '@CatchAuth'
    ],
    /**
    *  auth 认证
    *
    */
    'auth' => [
        // 默认
        'default' => [
        'guard' => 'admin',
    ],
    // 门面设置
    'guards' => [
        // admin 认证
        'admin' => [
            'driver' => 'jwt',
            'provider' => 'admin_users',
        ],
        // 开发者认证
        'developer' => [
            'driver' => 'jwt',
            'provider' => 'developer',
        ],
    ],
    // 服务提供
    'providers' => [
        // 后台用户认证服务
        'admin_users' => [
                'driver' => 'orm',
                'model' =>  \catchAdmin\permissions\model\Users::class,
            ],
        // 开发这认证服务
        'developer' => [
            'driver'=> 'orm',
            'model' => \catchAdmin\system\model\Developers::class
            ]
        ],
    ],

    /**
    * 自定义验证规则
    *
    */
    'validates' => [
        \catcher\validates\Sometimes::class,
        \catcher\validates\SensitiveWord::class,
    ],
    /**
    * 上传设置
    *
    */
    'upload' => [
        'image' => 'fileSize:' . 1024 * 1024 * 5 . '|fileExt:jpg,png,gif,jpeg',
        'file' => 'fileSize:' . 1024 * 1024 * 10 . '|fileExt:txt,pdf,xlsx,xls,html,mp4,mp3,amr'
    ],
];
