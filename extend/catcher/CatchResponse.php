<?php
namespace catcher;

use think\facade\View;
use think\Paginator;
use think\Response;

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
    public static function success($data = [], $msg = 'success', $code = 10000): \think\response\Json
    {
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ]);
    }

    /**
     * 分页
     *
     * @time 2019年12月06日
     * @param Paginator $list
     * @return \think\response\Json
     */
    public static function paginate(Paginator $list): \think\response\Json
    {
        return json([
            'code'  => 10000,
            'msg'   => 'success',
            'count' => $list->total(),
            'current' => $list->currentPage(),
            'data'    => $list->getCollection(),
        ]);
    }

    /**
     * 错误的响应
     *
     * @time 2019年12月02日
     * @param string $msg
     * @param int $code
     * @return mixed
     * @throws \Exception
     */
    public static function fail($msg = '', $code = 10001)
    {
        if (request()->isAjax()) {
            return json([
                'code' => $code,
                'msg'  => $msg,
            ]);
        }

        return Response::create(config('catch.error'), 'view', $code)->assign(['msg' => $msg, 'code' => $code]);
    }
}
