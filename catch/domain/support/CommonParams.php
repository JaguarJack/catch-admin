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
namespace catchAdmin\domain\support;

use catchAdmin\domain\support\signature\Aliyun;

/**
 * 公共参数
 *
 * Class CommonParams
 * @package catchAdmin\domain\support
 */
class CommonParams
{
    public static function aliyun(array $params, $method = 'GET')
    {
        date_default_timezone_set('UTC');

        $params = array_merge($params, [
            'Format' => 'json',
            'Version' =>  '2015-01-09',
            'AccessKeyId' => config('catch.domains.aliyun.access_key'),
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => date('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid()
        ]);

        $params['Signature'] = (new Aliyun($params))->signature($method);

        return $params;
    }
}