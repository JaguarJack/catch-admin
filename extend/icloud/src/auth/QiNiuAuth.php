<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 10:30
 */
namespace thinking\icloud\auth;

use thinking\icloud\Utility;

final class QiniuAuth
{
    use Utility;

    /**
     * 授权凭证
     *
     * @time at 2019年01月26日
     * @param string $uri
     * @param string $method
     * @return array
     */
    public static function authorization(string $uri, string $method)
    {
        return ['Authorization' => sprintf('QBox %s', self::getAccessToken($uri, '', 'application/x-www-form-urlencoded'))];
    }

    /**
     * 管理 Token
     *
     * @time at 2019年01月26日
     * @param string $urlString
     * @param string $body
     * @param string $contentType
     * @return string
     */
    public static function getAccessToken(string $urlString, string $body, string $contentType = '')
    {
        $appKey = config('cloud.qiniu.appKey');
        $appSecret = config('cloud.qiniu.appSecret');

        $url = parse_url($urlString);
        $data = '';
        if (array_key_exists('path', $url)) {
            $data = $url['path'];
        }
        if (array_key_exists('query', $url)) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";
        if ($body && $contentType === 'application/x-www-form-urlencoded') {
            $data .= $body;
        }
        $data = hash_hmac('sha1', $data, $appSecret, true);
        $encodedSign = self::urlSafeBase64Encode($data);
        $accessToken = sprintf('%s:%s', $appKey, $encodedSign);
        return $accessToken;
    }

    /**
     * 获取上传凭证
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $key
     * @param int $expires
     * @param string $policy
     * @param bool $strictPolicy
     * @return string
     */
    public static function uploadToken(
        string $bucket,
        string $key = '',
        int $expires = 3600,
        string $policy = '',
        bool $strictPolicy = true
    ){
        $appKey = config('cloud.qiniu.appKey');
        $appSecret = config('cloud.qiniu.appSecret');

        $scope = $key ? sprintf('%s:%s', $bucket, $key) : $bucket;
        $deadline = time() + $expires;
        $args = self::copyPolicy($args, $policy, $strictPolicy);

        $args['scope'] = $scope;
        $args['deadline'] = $deadline;

        $encodedPutPolicy = self::urlSafeBase64Encode(json_encode($args));
        $sign             = hash_hmac('sha1', $encodedPutPolicy, $appSecret, true);
        $encodedSign      = self::urlSafeBase64Encode($sign);

        return sprintf('%s:%s:%s', $appKey, $encodedSign, $encodedPutPolicy);
    }

    private static function copyPolicy(&$policy, $originPolicy, $strictPolicy)
    {
        if (!$originPolicy) {
            return [];
        }

        $policyFields = config('cloud.qiniu.policyFields');

        foreach ($originPolicy as $key => $value) {
            if (!$strictPolicy || in_array((string)$key, $policyFields, true)) {
                $policy[$key] = $value;
            }
        }

        return $policy;
    }

    /**
     * 下载凭证
     *
     * @time at 2019年01月26日
     * @param string $uri
     * @param int $expires
     * @return string
     */
    public static function dowmloadToken(string $uri, int $expires = 3600)
    {
        $appSecret = config('cloud.qiniu.appSecret');
        $appKey    = config('cloud.qiniu.appKey');

        $uri = sprintf('%s?e=%s', $uri, time() + $expires);

        $sign = hash_hmac('sha1', $uri, $appSecret, true);

        $encodedSign = self::urlSafeBase64Encode($sign);

        return sprintf('%s:%s', $appKey, $encodedSign);

    }
}