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
use catchAdmin\domain\support\signature\Qcloud;

/**
 * 公共参数
 *
 * Class CommonParams
 * @package catchAdmin\domain\support
 */
class CommonParams
{
    /**
     * 阿里云公共参数
     *
     * @param array $params
     * @param string $method
     * @return array
     */
    public static function aliyun(array $params, $method = 'GET')
    {
        date_default_timezone_set('UTC');

        $params = array_merge($params, [
            'Format' => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => config('catch.domains.aliyun.access_key'),
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => date('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid()
        ]);

        $params['Signature'] = (new Aliyun($params))->signature($method);

        return $params;
    }

    /**
     * 腾讯云公共参数
     *
     * @param array $params
     * @param string $method
     * @return array
     */
    public static function qcloud(array $params, $method = 'GET')
    {
        $params = array_merge($params, [
            'SecretId' => config('catch.domains.qcloud.access_key'),
            'SignatureMethod' => 'HmacSHA1',
            'Timestamp' => time(),
            'Nonce' => uniqid()
        ]);

        $params['Signature'] = (new Qcloud($params))->signature($method);

        return $params;
    }
}