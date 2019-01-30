<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 14:41
 */

return [
    'driver' => [
        'default' => 'qiniu',
        // 七牛驱动
        'qiniu'  => \thinking\icloud\cloud\QiNiuCloud::class,
        // 又拍驱动
        'uppay' => \thinking\icloud\cloud\UpYunCloud::class,
        // 七牛驱动认证
        'qiniuAuth' => \thinking\icloud\auth\QiNiuAuth::class,
        // 又拍驱动认证
        'uppayAuth' => \thinking\icloud\auth\UpYunAuth::class,
    ],

    /* 七牛配置信息 */
    'qiniu'  => [
        'app_key'    => '',
        'app_secret' => '',

        //上传策略字段，上传凭证校验使用
        'policyFields' => [
            'callbackUrl',
            'callbackBody',
            'callbackHost',
            'callbackBodyType',
            'callbackFetchKey',
            'returnUrl',
            'returnBody',
            'endUser',
            'saveKey',
            'insertOnly',
            'detectMime',
            'mimeLimit',
            'fsizeMin',
            'fsizeLimit',
            'persistentOps',
            'persistentNotifyUrl',
            'persistentPipeline',
            'deleteAfterDays',
            'fileType',
            'isPrefixalScope',
        ],
    ],

    /* 又拍云配置信息 */
    'upyun'  =>  [
        'opreator'  => '',
        'password'  => '',

        'buckets'   => [''],
    ],
    'oss' => [
      'access_key' => '',
      'access_secret' => '',
    ],
    //api接口
    'host' => [
        //七牛host
        'rs'    => 'rs.qbox.me',
        'api'   => 'api.qiniu.com',
        'uc'    => 'uc.qbox.me',
        'rsf'   => 'rsf.qbox.me',
        'iovip' => 'iovip.qbox.me',
        'up'    => 'up.qiniu.com',
        //又拍host
        'v0'    => 'v0.api.upyun.com',
        'v1'    => 'v1.api.upyun.com',
        'v2'    => 'v2.api.upyun.com',
        'v3'    => 'v3.api.upyun.com',
    ],
];