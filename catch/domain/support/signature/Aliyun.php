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
namespace catchAdmin\domain\support\signature;

class Aliyun
{
    /**
     * @var array
     */
    protected $params;

    /**
     * Aliyun constructor.
     * @param $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * encode
     *
     * @time 2020年09月25日
     * @param $str
     * @return string|string[]|null
     */
    protected function percentEncode(string $str)
    {
        return preg_replace(['/\+/', '/\*/', '/%7E/'], ['%20', '%2A', '~'], urlencode($str));
    }

    /**
     * 签名
     *
     * @time 2020年09月25日
     * @param $method
     * @return string
     */
    public function signature(string $method)
    {
        ksort($this->params);

        $queryString = '';

        foreach ($this->params as $key => $param) {
            $queryString .= '&' . $this->percentEncode($key) . '=' . $this->percentEncode($param);
        }

        $signString = $method . '&' .
                      $this->percentEncode('/') . '&' .
                      $this->percentEncode(substr($queryString, 1));

        return base64_encode(hash_hmac('sha1', $signString, config('catch.domains.aliyun.access_secret'). '&', true));
    }
}