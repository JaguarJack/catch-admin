<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 10:31
 */
namespace thinking\icloud\auth;

class UpYunAuth
{
    /**
     * 签名
     *
     * @time at 2019年01月26日
     * @param string $uri
     * @param string $method
     * @param string $contentMD5
     * @return array
     */
    public static function authorization(string $uri, string $method,  string $contentMD5= '')
    {
        $date = gmdate('D, d M Y H:i:s \G\M\T');

        $singArr = [$method, parse_url($uri)['path'], $date];
        if ($contentMD5) $singArr[] = $contentMD5;
        $sign = base64_encode(hash_hmac('sha1', implode('&', $singArr), md5(config('cloud.upyun.password')), true));

        return [
            'Authorization' => sprintf('UPYUN %s:%s', config('cloud.upyun.opreator'), $sign),
            'Date'          => $date,
        ];
    }


    /**
     * 获取 token
     *
     * @time at 2019年01月26日
     * @param string $method
     * @param int $expire
     * @param string $uriPrefix
     * @param string $uriPostfix
     * @return string
     */
    public static function uploadToken(string $method, int $expire = 3888000, string $uriPrefix = '', string $uriPostfix= '')
    {
        $operator = config('cloud.upyun.opreator');
        $password = config('cloud.upyun.password');

        $tokenArr = [$operator, $password, $method, $expire];

        if ($uriPrefix) $tokenArr[]  = $uriPrefix;
        if ($uriPostfix) $tokenArr[] = $uriPostfix;

        $token = base64_encode(hash_hmac('sha1',implode('&', $tokenArr) , $password, true));

        return sprintf('UPYUN %s:%s', $operator, $token);
    }
}