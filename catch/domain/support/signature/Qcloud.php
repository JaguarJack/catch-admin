<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catchAdmin\domain\support\signature;

class Qcloud
{
    /**
     * @var array
     */
    protected $params;

    /**
     * Qcloud constructor.
     * @param $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * key 替换
     *
     * @param $key
     * @return mixed|string|string[]
     */
    protected function replaceKey($key)
    {
        return strpos($key, '_') === false ?
            $key : str_replace('_', '.', $key);
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
            $queryString .= '&' . $this->replaceKey($key) . '=' . $param;
        }

        $signString = $method . config('catch.domains.qcloud.api_domain') . '/v2/index.php?'
                              . substr($queryString, 1);

        return base64_encode(hash_hmac('sha1', $signString, config('catch.domains.qcloud.access_secret'), true));
    }
}