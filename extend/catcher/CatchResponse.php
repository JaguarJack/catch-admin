<?php
namespace catcher;

class CatchResponse
{
    /**
     * 成功的响应
     *
     * @time 2019年12月02日
     * @param array $data
     * @param $msg
     * @param int $code
     * @return \think\response\Json
     */
    public static function success($data = [], $msg = '', $code = 10000): \think\response\Json
    {
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ]);
    }

    /**
     * 错误的响应
     *
     * @time 2019年12月02日
     * @param string $msg
     * @param int $code
     * @return \think\response\Json
     */
    public static function fail($msg = '', $code = 10001): \think\response\Json
    {
        return json([
            'code' => $code,
            'msg'  => $msg,
        ]);
    }
}
