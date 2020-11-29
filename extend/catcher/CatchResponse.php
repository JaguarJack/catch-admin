<?php
declare(strict_types=1);

namespace catcher;

use think\Paginator;
use think\response\Json;

class CatchResponse
{
  /**
   * 成功的响应
   *
   * @time 2019年12月02日
   * @param array $data
   * @param $msg
   * @param int $code
   * @return Json
   */
  public static function success($data = [], $msg = 'success', $code = Code::SUCCESS): Json
  {
        return json([
          'code'    => $code,
          'message' => $msg,
          'data'    => $data,
        ]);
  }

  /**
   * 分页
   *
   * @time 2019年12月06日
   * @param Paginator $list
   * @return
   */
  public static function paginate(Paginator $list)
  {
        return json([
          'code'    => Code::SUCCESS,
          'message' => 'success',
          'count'   => $list->total(),
          'current' => $list->currentPage(),
          'limit'   => $list->listRows(),
          'data'    => $list->getCollection(),
        ]);
  }

  /**
   * 错误的响应
   *
   * @time 2019年12月02日
   * @param string $msg
   * @param int $code
   * @return Json
   */
  public static function fail($msg = '', $code = Code::FAILED): Json
  {
        return json([
            'code' => $code,
            'message'  => $msg,
        ]);
  }
}
