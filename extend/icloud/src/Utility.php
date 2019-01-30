<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 10:37
 */

namespace thinking\icloud;

trait Utility
{
    /**
     * URL Base64 加密
     *
     * @time at 2019年01月26日
     * @param string $string
     * @return mixed
     */
    public static function urlSafeBase64Encode(string $string)
    {
        return str_replace(['+','/'], ['-','_'], base64_encode($string));
    }

    /**
     * 获取接口 HOST
     *
     * @time at 2019年01月26日
     * @param string $host
     * @param bool $isHttps
     * @return string
     */
    public static function getHost(string $host = 'rs', bool $isHttps = false)
    {
        return $isHttps ? 'https://' : 'http://' . config('cloud.host.'. strtolower($host));
    }

    /**
     * csc32 校验
     *
     * @time at 2019年01月26日
     * @param string $data
     * @return string
     */
    public static function crc32_data(string $data)
    {
        $hash = hash('crc32b', $data);
        $array = unpack('N', pack('H*', $hash));
        return sprintf('%u', $array[1]);
    }
}