<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/30
 * Time: 17:25
 */
namespace app\exceptions;

use Exception;
use think\exception\HttpException;

class Handle extends \think\exception\Handle
{
    /**
     * rewrite render
     *
     * @time at 2018年11月30日
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(Exception $e)
    {
        switch (true) {
            case $e instanceof HttpException:
                return $this->error($e->getMessage());
                break;
            default:
                return $this->convertExceptionToResponse($e);
                break;
        }
    }

    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        return json([
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ]);
    }
}