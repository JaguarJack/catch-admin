<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 11:21
 */
return [
    'default' => 'qq',

    'qq' => [
        'app_id' => '101540418',
        'app_secret' => 'fae33241fa62f8b375565365e216cde7',
        'redirect_url' => 'http://127.0.0.1:8000/oauth',
    ],

    'weibo' => [
        'app_id'       => '2539916570',
        'app_secret'   => 'aae0e36c96ecc04f873be3e0dc06f71b',
        'redirect_url' => 'http://www.rllady.com/home/index/sinaLogin',
    ],

    'github' => [
        'app_id'       => '5b8a9a7e8dded81c128d',
        'app_secret'   => '12657a874d3c036a5b9e55c7512ffd5292e67118',
        'redirect_url' => 'http://127.0.0.1:8000/oauth',
    ],

    'wx' => [
        'app_id'       => 'wx83f3fcd9e0cfdff8',
        'app_secret'   => '2a736290f3af0fb40710a65c938b40b3',
        'redirect_url' => 'http://127.0.0.1:8000/oauth',
        'scope'        => 'snsapi_login',
    ],
];